<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use App\Models\Inventory;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Events\MaterialRequestUpdated;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialRequestExport;

class MaterialRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Batasi akses untuk fitur tertentu agar tidak bisa diakses oleh admin_logistic
        $this->middleware(function ($request, $next) {
            if (
                in_array($request->route()->getName(), ['material_requests.create', 'material_requests.store', 'material_requests.bulk_create', 'material_requests.bulk_store', 'material_requests.destroy']) &&
                auth()->user()->role === 'admin_logistic'
            ) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        })->only(['create', 'store', 'bulkCreate', 'bulkStore', 'destroy']);
    }

    public function index(Request $request)
    {
        $query = MaterialRequest::with('inventory', 'project');

        // Apply filters
        if ($request->has('project') && $request->project !== null) {
            $query->where('project_id', $request->project);
        }

        if ($request->has('material') && $request->material !== null) {
            $query->where('inventory_id', $request->material);
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        if ($request->has('requested_by') && $request->requested_by !== null) {
            $query->where('requested_by', $request->requested_by);
        }

        if ($request->has('requested_at') && $request->requested_at !== null) {
            $query->whereDate('created_at', $request->requested_at);
        }

        $requests = $query->orderBy('created_at', 'desc')->get()->map(function ($request) {
            $request->created_at = $request->created_at->format('Y-m-d, H:i'); // Format lokal
            return $request;
        });

        // Pass data for filters
        $projects = Project::orderBy('name')->get();
        $materials = Inventory::orderBy('name')->get();
        $users = User::orderBy('username')->get();

        return view('material_requests.index', compact('requests', 'projects', 'materials', 'users'));
    }

    public function export(Request $request)
    {
        // Ambil filter dari request
        $project = $request->project;
        $material = $request->material;
        $status = $request->status;
        $requestedBy = $request->requested_by;
        $requestedAt = $request->requested_at;

        // Filter data berdasarkan request
        $query = MaterialRequest::with('inventory', 'project');

        if ($project) {
            $query->where('project_id', $project);
        }

        if ($material) {
            $query->where('inventory_id', $material);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($requestedBy) {
            $query->where('requested_by', $requestedBy);
        }

        if ($requestedAt) {
            $query->whereDate('created_at', $requestedAt);
        }

        $requests = $query->get();

        // Buat nama file dinamis
        $fileName = 'material_requests';
        if ($project) {
            $projectName = Project::find($project)->name ?? 'Unknown Project';
            $fileName .= '_project-' . str_replace(' ', '-', strtolower($projectName));
        }
        if ($material) {
            $materialName = Inventory::find($material)->name ?? 'Unknown Material';
            $fileName .= '_material-' . str_replace(' ', '-', strtolower($materialName));
        }
        if ($status) {
            $fileName .= '_status-' . strtolower($status);
        }
        if ($requestedBy) {
            $fileName .= '_requested_by-' . strtolower($requestedBy);
        }
        if ($requestedAt) {
            $fileName .= '_requested_at-' . $requestedAt;
        }
        $fileName .= '_' . now()->format('Y-m-d') . '.xlsx';

        // Ekspor data menggunakan kelas MaterialRequestExport
        return Excel::download(new MaterialRequestExport($requests), $fileName);
    }

    public function create()
    {
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('material_requests.create', compact('inventories', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'qty' => 'required|numeric|min:0.01',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        // Validasi: Pastikan qty tidak melebihi quantity inventory
        if ($request->qty > $inventory->quantity) {
            return back()->withInput()->withErrors(['qty' => 'Requested quantity cannot exceed available inventory quantity.']);
        }

        $user = auth()->user();
        $department = match ($user->role) {
            'admin_mascot' => 'mascot',
            'admin_costume' => 'costume',
            'admin_logistic' => 'logistic',
            'admin_finance' => 'finance',
            'super_admin' => 'management',
            default => 'general',
        };

        $materialRequest = MaterialRequest::create([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'qty' => $request->qty,
            'requested_by' => $user->username,
            'department' => $department,
            'remark' => $request->remark,
        ]);

        // Trigger event
        event(new MaterialRequestUpdated($materialRequest, 'created'));

        return redirect()->route('material_requests.index')->with('success', "Material Request Created Successfully");
    }

    public function bulkCreate()
    {
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('material_requests.bulk_create', compact('inventories', 'projects'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'requests.*.inventory_id' => 'required|exists:inventories,id',
            'requests.*.project_id' => 'required|exists:projects,id',
            'requests.*.qty' => 'required|numeric|min:0.01',
        ]);

        $user = auth()->user();
        $department = match ($user->role) {
            'admin_mascot' => 'mascot',
            'admin_costume' => 'costume',
            'admin_logistic' => 'logistic',
            'admin_finance' => 'finance',
            'super_admin' => 'management',
            default => 'general',
        };

        $createdRequests = []; // Array untuk menyimpan material request yang dibuat

        foreach ($request->requests as $index => $req) {
            $inventory = Inventory::findOrFail($req['inventory_id']);

            // Validasi: Pastikan qty tidak melebihi stok yang tersedia
            if ($req['qty'] > $inventory->quantity) {
                return back()->withInput()->withErrors([
                    "requests.$index.qty" => "Quantity exceeds stock for '{$inventory->name}'."
                ]);
            }

            $materialRequest = MaterialRequest::create([
                'inventory_id' => $req['inventory_id'],
                'project_id' => $req['project_id'],
                'qty' => $req['qty'],
                'requested_by' => $user->username,
                'department' => $department,
                'remark' => $req['remark'] ?? null,
            ]);

            $createdRequests[] = $materialRequest; // Tambahkan ke array

            // Trigger event untuk setiap material request
            event(new MaterialRequestUpdated($materialRequest, 'created'));
        }

        if ($request->ajax()) {
            // Jika permintaan berasal dari AJAX, kembalikan JSON response
            return response()->json([
                'success' => true,
                'message' => 'Bulk material requests submitted!',
                'created_requests' => $createdRequests,
            ]);
        }

        // Jika bukan AJAX, redirect ke halaman daftar material request
        return redirect()->route('material_requests.index')->with('success', "Bulk material requests submitted successfully!");
    }

    public function edit($id)
    {
        // Ambil data Material Request berdasarkan ID
        $request = MaterialRequest::with('inventory', 'project')->findOrFail($id);

        // Validasi: Pastikan hanya Material Request dengan status tertentu yang bisa diedit
        if ($request->status !== 'pending') {
            return redirect()->route('material_requests.index')->with('error', "Only pending requests can be edited.");
        }

        if ($request->status === 'canceled') {
            return redirect()->route('material_requests.index')->with('error', "Canceled requests cannot be edited.");
        }

        // Validasi: Pastikan inventory dan project terkait masih ada
        if (!$request->inventory || !$request->project) {
            return redirect()->route('material_requests.index')->with('error', "The associated inventory or project no longer exists.");
        }

        // Ambil data tambahan untuk dropdown
        $inventories = Inventory::orderBy('name')->get()->map(function ($inventory) {
            $inventory->available_quantity = $inventory->quantity; // Tambahkan stok tersedia
            return $inventory;
        });

        $projects = Project::orderBy('name')->get();

        // Tampilkan view edit dengan data yang diperlukan
        return view('material_requests.edit', compact('request', 'inventories', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        // Jika hanya status yang diperbarui
        if ($request->has('status') && $request->keys() === ['_token', '_method', 'status']) {
            $request->validate([
                'status' => 'required|in:pending,approved,delivered,canceled',
            ]);

            $materialRequest->update([
                'status' => $request->status,
            ]);

            // Trigger event
            event(new MaterialRequestUpdated($materialRequest, 'updated'));

            return redirect()->route('material_requests.index')->with('success', "Status updated successfully.");
        }

        // Validasi untuk pembaruan lengkap
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'qty' => 'required|numeric|min:0.01',
            'status' => 'required|in:pending,approved,delivered,canceled',
            'remark' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        // Validasi: Pastikan qty tidak melebihi stok yang tersedia
        if ($request->qty > $inventory->quantity) {
            return back()->withInput()->withErrors(['qty' => 'Requested quantity cannot exceed available inventory quantity.']);
        }

        if ($materialRequest->status === 'canceled') {
            return redirect()->route('material_requests.index')->with('error', "Canceled requests cannot be updated.");
        }

        $materialRequest->update([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'qty' => $request->qty,
            'status' => $request->status,
            'remark' => $request->remark,
        ]);

        // Trigger event
        event(new MaterialRequestUpdated($materialRequest, 'updated'));

        return redirect()->route('material_requests.index')->with('success', "Material Request updated successfully.");
    }

    public function destroy($id)
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        if ($materialRequest->status === 'canceled') {
            return redirect()->route('material_requests.index')->with('error', "Canceled requests cannot be deleted.");
        }

        // Trigger event
        event(new MaterialRequestUpdated($materialRequest, 'deleted'));

        $materialRequest->delete();

        return back()->with('success', "Material Request deleted successfully.");
    }
}