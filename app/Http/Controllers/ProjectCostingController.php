<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\GoodsOut;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectCostingExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;


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
            ->with(['inventory' => function ($q) {
                $q->withTrashed()->with('currency');
            }])
            ->get();

        // Hitung total biaya per material dan konversi ke IDR
        $materials = $materials->map(function ($item) {
            $inventory = $item->inventory;

            // Jika inventory null, coba ambil dari trash
            if (!$inventory) {
                $deletedInventory = Inventory::withTrashed()->find($item->inventory_id);
                $inventory = (object) [
                    'name' => $deletedInventory ? $deletedInventory->name . ' (deleted)' : 'N/A',
                    'price' => 0,
                    'unit' => 'N/A',
                    'currency' => (object) ['name' => 'N/A', 'exchange_rate' => 1]
                ];
            } else {
                // Jika inventory ada, cek field kosong
                $inventory->name = $inventory->name ?? 'N/A';
                if (method_exists($inventory, 'trashed') && $inventory->trashed()) {
                    $inventory->name .= ' (deleted)';
                }
                $inventory->price = $inventory->price ?? 0;
                $inventory->unit = $inventory->unit ?? 'N/A';
                $inventory->currency = $inventory->currency ?? (object)['name' => 'N/A', 'exchange_rate' => 1];
                $inventory->currency->name = $inventory->currency->name ?? 'N/A';
                $inventory->currency->exchange_rate = $inventory->currency->exchange_rate ?? 1;
            }

            $price = $inventory->price ?? 0;
            $quantity = $item->quantity ?? 0;
            $exchangeRate = $inventory->currency->exchange_rate ?? 1;

            $totalCost = $price * $quantity;
            $totalCostInIDR = $totalCost * $exchangeRate;

            $item->inventory = $inventory;
            $item->total_price = $totalCost;
            $item->total_cost = $totalCostInIDR;
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

        // Ambil semua material yang digunakan dalam proyek, termasuk inventory yang sudah di-soft delete
        $materials = GoodsOut::where('project_id', $project_id)
            ->with(['inventory' => function ($q) {
                $q->withTrashed();
            }])
            ->get();

        // Hitung total biaya per material
        $materials = $materials->map(function ($item) {
            $inventory = $item->inventory;

            // Jika inventory null (sudah dihapus permanen), handle agar tidak error
            $price = $inventory ? $inventory->price : 0;
            $name = $inventory ? $inventory->name : 'N/A';
            if ($inventory && method_exists($inventory, 'trashed') && $inventory->trashed()) {
                $name .= ' (deleted)';
            }
            $item->inventory_name = $name;
            $item->total_cost = $item->quantity * $price; // Harga langsung dianggap IDR
            return $item;
        });

        // Ekspor ke Excel
        return Excel::download(new ProjectCostingExport($materials, $project->name), 'project_costing_' . $project->name . '.xlsx');
    }
}

// If you want to log $materials, place this inside a controller method where $materials is defined, for example:
// Log::info('Material Data:', $materials->toArray());
