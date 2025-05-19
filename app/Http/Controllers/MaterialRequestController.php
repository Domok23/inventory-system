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

        foreach ($request->requests as $req) {
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
        $request = MaterialRequest::findOrFail($id);
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('material_requests.edit', compact('request', 'inventories', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $req = MaterialRequest::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,approved',
        ]);
        $req->update($request->only('inventory_id', 'project_id', 'qty', 'status', 'remark'));

        return redirect()->route('material_requests.index')->with('success', 'Updated');
    }

    public function destroy($id)
    {
        MaterialRequest::findOrFail($id)->delete();
        return back()->with('success', 'Deleted');
    }
}
