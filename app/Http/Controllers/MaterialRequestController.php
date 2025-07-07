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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

        $requests = $query->orderBy('created_at', 'desc')->get();

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

    public function create(Request $request)
    {
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        // Periksa apakah parameter material_id ada
        $selectedMaterial = null;
        if ($request->has('material_id')) {
            $selectedMaterial = Inventory::find($request->material_id);
        }

        return view('material_requests.create', compact('inventories', 'projects', 'selectedMaterial'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'qty' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $department = match ($user->role) {
            'admin_mascot' => 'mascot',
            'admin_costume' => 'costume',
            'admin_logistic' => 'logistic',
            'admin_finance' => 'finance',
            'super_admin' => 'management',
            default => 'general',
        };

        DB::beginTransaction();
        try {
            // Lock inventory row
            $inventory = Inventory::where('id', $request->inventory_id)->lockForUpdate()->first();

            // Validasi stok
            if ($request->qty > $inventory->quantity) {
                DB::rollBack();
                return back()->withInput()->withErrors(['qty' => 'Requested quantity cannot exceed available inventory quantity.']);
            }

            $materialRequest = MaterialRequest::create([
                'inventory_id' => $request->inventory_id,
                'project_id' => $request->project_id,
                'qty' => $request->qty,
                'requested_by' => $user->username,
                'department' => $department,
                'remark' => $request->remark,
            ]);

            // (Opsional) Kurangi stok jika memang ingin langsung mengurangi
            // $inventory->quantity -= $request->qty;
            // $inventory->save();

            DB::commit();

            // Trigger event
            event(new MaterialRequestUpdated($materialRequest, 'created'));

            $project = Project::findOrFail($request->project_id);

            return redirect()->route('material_requests.index')->with(
                'success',
                "Material Request for <b>{$inventory->name}</b> in project <b>{$project->name}</b> created successfully!"
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['qty' => 'Failed to create request: ' . $e->getMessage()]);
        }
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

        $user = Auth::user();
        $department = match ($user->role) {
            'admin_mascot' => 'mascot',
            'admin_costume' => 'costume',
            'admin_logistic' => 'logistic',
            'admin_finance' => 'finance',
            'super_admin' => 'management',
            default => 'general',
        };

        $createdRequests = [];
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($request->requests as $index => $req) {
                // Lock inventory row
                $inventory = Inventory::where('id', $req['inventory_id'])->lockForUpdate()->first();

                // Validasi stok
                if ($req['qty'] > $inventory->quantity) {
                    $errors["requests.$index.qty"] = "Quantity exceeds stock for '{$inventory->name}'.";
                } else {
                    $materialRequest = MaterialRequest::create([
                        'inventory_id' => $req['inventory_id'],
                        'project_id' => $req['project_id'],
                        'qty' => $req['qty'],
                        'processed_qty' => 0,
                        'requested_by' => $user->username,
                        'department' => $department,
                        'remark' => $req['remark'] ?? null,
                    ]);
                    $createdRequests[] = $materialRequest;
                }
            }

            if (!empty($errors)) {
                DB::rollBack();
                return back()->withInput()->withErrors($errors);
            }

            DB::commit();

            // Trigger event SEKALI SAJA setelah commit
            if (!empty($createdRequests)) {
                event(new MaterialRequestUpdated($createdRequests, 'created'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['bulk' => 'Bulk request failed: ' . $e->getMessage()]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Bulk material requests submitted!',
                'created_requests' => $createdRequests,
            ]);
        }

        $infoList = [];
        foreach ($createdRequests as $req) {
            $infoList[] = "<b>{$req->inventory->name}</b> in project <b>{$req->project->name}</b>";
        }
        $infoString = implode(', ', $infoList);

        return redirect()->route('material_requests.index')->with(
            'success',
            "Bulk material requests submitted successfully for: {$infoString}"
        );
    }

    public function edit(Request $request, $id)
    {
        $materialRequest = MaterialRequest::with('inventory', 'project')->findOrFail($id);

        $filters = [
            'project' => $request->input('filter_project'),
            'material' => $request->input('filter_material'),
            'status' => $request->input('filter_status'),
            'requested_by' => $request->input('filter_requested_by'),
            'requested_at' => $request->input('filter_requested_at'),
        ];
        $filters = array_filter($filters, fn($v) => !is_null($v) && $v !== '');

        // Validasi: Pastikan hanya Material Request dengan status tertentu yang bisa diedit
        if ($materialRequest->status !== 'pending') {
            return redirect()->route('material_requests.index', $filters)->with('error', "Only pending requests can be edited.");
        }

        if ($materialRequest->status === 'canceled') {
            return redirect()->route('material_requests.index', $filters)->with('error', "Canceled requests cannot be edited.");
        }

        if (!$materialRequest->inventory || !$materialRequest->project) {
            return redirect()->route('material_requests.index', $filters)->with('error', "The associated inventory or project no longer exists.");
        }

        $inventories = Inventory::orderBy('name')->get()->map(function ($inventory) {
            $inventory->available_quantity = $inventory->quantity;
            return $inventory;
        });

        $projects = Project::orderBy('name')->get();

        return view('material_requests.edit', [
            'request' => $materialRequest,
            'inventories' => $inventories,
            'projects' => $projects,
        ]);
    }

    public function update(Request $request, $id)
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        // Jika hanya status yang diperbarui (inline dari tabel)
        if ($request->has('status') && !$request->has('inventory_id')) {
            $request->validate([
                'status' => 'required|in:pending,approved,delivered,canceled',
            ]);

            // Ambil data sebelum update
            $oldStatus = $materialRequest->status;
            $projectName = $materialRequest->project ? $materialRequest->project->name : '-';
            $materialName = $materialRequest->inventory ? $materialRequest->inventory->name : '-';

            $materialRequest->update([
                'status' => $request->status,
            ]);

            event(new MaterialRequestUpdated($materialRequest, 'status'));

            $filters = [
                'project' => $request->input('filter_project'),
                'material' => $request->input('filter_material'),
                'status' => $request->input('filter_status'),
                'requested_by' => $request->input('filter_requested_by'),
                'requested_at' => $request->input('filter_requested_at'),
            ];
            $filters = array_filter($filters, fn($v) => !is_null($v) && $v !== '');

            // Ambil status akhir
            $newStatus = $materialRequest->status;

            return redirect()->route('material_requests.index', $filters)->with(
                'success',
                "Material Request status for <b>{$materialName}</b> in project <b>{$projectName}</b> updated from <b>" . ucfirst($oldStatus) . "</b> to <b>" . ucfirst($newStatus) . "</b>."
            );
        }

        // Validasi untuk pembaruan lengkap
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'qty' => 'required|numeric|min:0.01',
            'status' => 'required|in:pending,approved,delivered,canceled',
            'remark' => 'nullable|string',
        ]);

        $filters = [
            'project' => $request->input('filter_project'),
            'material' => $request->input('filter_material'),
            'status' => $request->input('filter_status'),
            'requested_by' => $request->input('filter_requested_by'),
            'requested_at' => $request->input('filter_requested_at'),
        ];
        $filters = array_filter($filters, fn($v) => !is_null($v) && $v !== '');

        if ($materialRequest->status === 'canceled') {
            return redirect()->route('material_requests.index', $filters)->with('error', "Canceled requests cannot be updated.");
        }

        DB::beginTransaction();
        try {
            // Lock inventory row
            $inventory = Inventory::where('id', $request->inventory_id)->lockForUpdate()->first();

            // Validasi stok
            if ($request->qty > $inventory->quantity) {
                DB::rollBack();
                return back()->withInput()->withErrors(['qty' => 'Requested quantity cannot exceed available inventory quantity.']);
            }

            $materialRequest->update([
                'inventory_id' => $request->inventory_id,
                'project_id' => $request->project_id,
                'qty' => $request->qty,
                'status' => $request->status,
                'remark' => $request->remark,
            ]);

            DB::commit();

            // Trigger event
            event(new MaterialRequestUpdated($materialRequest, 'updated'));

            $project = Project::findOrFail($request->project_id);
            return redirect()->route('material_requests.index', $filters)->with(
                'success',
                "Material Request for <b>{$inventory->name}</b> in project <b>{$project->name}</b> updated successfully."
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['qty' => 'Failed to update request: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $materialRequest = MaterialRequest::findOrFail($id);

        $filters = [
            'project' => $request->input('filter_project'),
            'material' => $request->input('filter_material'),
            'status' => $request->input('filter_status'),
            'requested_by' => $request->input('filter_requested_by'),
            'requested_at' => $request->input('filter_requested_at'),
        ];
        $filters = array_filter($filters, fn($v) => !is_null($v) && $v !== '');

        if ($materialRequest->status === 'canceled') {
            return redirect()->route('material_requests.index', $filters)->with('error', "Canceled requests cannot be deleted.");
        }

        // Trigger event
        event(new MaterialRequestUpdated($materialRequest, 'deleted'));

        $materialRequest->delete();

        $inventory = $materialRequest->inventory ?? Inventory::find($materialRequest->inventory_id);
        $project = $materialRequest->project ?? Project::find($materialRequest->project_id);

        return redirect()->route('material_requests.index', $filters)->with(
            'success',
            "Material Request for <b>{$inventory->name}</b> in project <b>{$project->name}</b> deleted successfully."
        );
    }
}
