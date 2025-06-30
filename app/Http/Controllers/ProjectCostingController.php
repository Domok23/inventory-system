<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\GoodsOut;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectCostingExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class ProjectCostingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin', 'admin_finance'];
            if (!in_array(Auth::user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Project::query();

        // Apply filters
        if ($request->has('department') && $request->department !== null) {
            $query->where('department', $request->department);
        }

        $projects = $query->orderBy('name')->get();

        // Pass data for filters
        $departments = Project::select('department')->distinct()->pluck('department');

        return view('costing.index', compact('projects', 'departments'));
    }

    public function viewCosting($project_id)
    {
        $project = Project::findOrFail($project_id);

        // Ambil semua material yang digunakan dalam proyek
        $materials = GoodsOut::where('project_id', $project_id)
            ->with(['inventory.currency']) // Pastikan relasi currency dimuat
            ->get();

        // Hitung total biaya per material dan konversi ke IDR
        $materials = $materials->map(function ($item) {
            if (!$item->inventory) {
                $item->inventory = (object) [
                    'name' => 'N/A',
                    'price' => 0,
                    'unit' => 'N/A',
                    'currency' => (object) ['name' => 'N/A', 'exchange_rate' => 1]
                ]; // Default jika inventory null
            }

            $price = $item->inventory->price ?? 0; // Harga per unit
            $quantity = $item->quantity ?? 0; // Jumlah material
            $exchangeRate = $item->inventory->currency->exchange_rate ?? 1; // Kurs ke IDR (default 1 jika tidak ada)

            $totalCost = $price * $quantity; // Total biaya sebelum konversi
            $totalCostInIDR = $totalCost * $exchangeRate; // Konversi ke IDR

            $item->total_price = $totalCost; // Simpan Total Price (Price x Quantity)
            $item->total_cost = $totalCostInIDR; // Simpan Total Cost dalam IDR
            return $item;
        });

        // Hitung grand total dalam IDR
        $grand_total_idr = $materials->sum('total_cost');

        return response()->json([
            'project' => $project->name,
            'materials' => $materials,
            'grand_total_idr' => $grand_total_idr,
        ]);
    }
    public function exportCosting($project_id)
    {
        $project = Project::findOrFail($project_id);

        // Ambil semua material yang digunakan dalam proyek
        $materials = GoodsOut::where('project_id', $project_id)
            ->with(['inventory']) // Hapus relasi currency jika tidak digunakan
            ->get();

        // Hitung total biaya per material
        $materials = $materials->map(function ($item) {
            $item->total_cost = $item->quantity * $item->inventory->price; // Harga langsung dianggap IDR
            return $item;
        });
        Log::info('Materials Data:', $materials->toArray());
        // Ekspor ke Excel
        return Excel::download(new ProjectCostingExport($materials, $project->name), 'project_costing_' . $project->name . '.xlsx');
    }
}

// If you want to log $materials, place this inside a controller method where $materials is defined, for example:
// Log::info('Material Data:', $materials->toArray());
