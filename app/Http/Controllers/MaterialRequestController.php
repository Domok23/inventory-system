<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use App\Models\Inventory;
use App\Models\Project;
use Illuminate\Http\Request;

class MaterialRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin', 'admin_mascot', 'admin_costume', 'admin_logistic'];
            if (!in_array(auth()->user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $requests = MaterialRequest::with('inventory', 'project')->orderBy('created_at', 'desc')->get();
        return view('material_requests.index', compact('requests'));
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
            'super_admin' => 'management',
            default => 'general',
        };

        MaterialRequest::create([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'qty' => $request->qty,
            'requested_by' => $user->username,
            'department' => $department,
            'remark' => $request->remark,
        ]);

        return redirect()->route('material_requests.index')->with('success', 'Material Request Created');
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
            'super_admin' => 'management',
            default => 'general',
        };

        foreach ($request->requests as $index => $req) {
            $inventory = Inventory::findOrFail($req['inventory_id']);

            // Validasi: Pastikan qty tidak melebihi stok yang tersedia
            if ($req['qty'] > $inventory->quantity) {
                return back()->withInput()->withErrors([
                    "requests.$index.qty" => "Quantity exceeds stock for '{$inventory->name}'."
                ]);
            }

            MaterialRequest::create([
                'inventory_id' => $req['inventory_id'],
                'project_id' => $req['project_id'],
                'qty' => $req['qty'],
                'requested_by' => $user->username,
                'department' => $department,
                'remark' => $req['remark'] ?? null,
            ]);
        }

        return redirect()->route('material_requests.index')->with('success', 'Bulk material requests submitted!');
    }

    public function edit($id)
    {
        // Ambil data Material Request berdasarkan ID
        $request = MaterialRequest::with('inventory', 'project')->findOrFail($id);

        // Validasi: Pastikan hanya Material Request dengan status tertentu yang bisa diedit
        if ($request->status !== 'pending') {
            return redirect()->route('material_requests.index')->with('error', 'Only pending requests can be edited.');
        }

        // Validasi: Pastikan inventory dan project terkait masih ada
        if (!$request->inventory || !$request->project) {
            return redirect()->route('material_requests.index')->with('error', 'The associated inventory or project no longer exists.');
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
        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:pending,approved,delivered',
            ]);

            $materialRequest->update([
                'status' => $request->status,
            ]);

            return redirect()->route('material_requests.index')->with('success', 'Status updated successfully.');
        }

        // Validasi untuk pembaruan lengkap
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'qty' => 'required|numeric|min:0.01',
            'status' => 'required|in:pending,approved,delivered',
            'remark' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);

        // Validasi: Pastikan qty tidak melebihi stok yang tersedia
        if ($request->qty > $inventory->quantity) {
            return back()->withInput()->withErrors(['qty' => 'Requested quantity cannot exceed available inventory quantity.']);
        }

        $materialRequest->update([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'qty' => $request->qty,
            'status' => $request->status,
            'remark' => $request->remark,
        ]);

        return redirect()->route('material_requests.index')->with('success', 'Material Request updated successfully.');
    }

    public function destroy($id)
    {
        MaterialRequest::findOrFail($id)->delete();
        return back()->with('success', 'Deleted');
    }
}
