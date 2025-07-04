<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\GoodsOut;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\MaterialRequest;
use App\Helpers\MaterialUsageHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GoodsOutExport;
use Illuminate\Support\Facades\Auth;

class GoodsOutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Batasi akses untuk fitur tertentu agar hanya bisa diakses oleh admin_logistic dan super_admin
        $this->middleware(function ($request, $next) {
            if (
                in_array($request->route()->getName(), [
                    'goods_out.create_with_id',
                    'goods_out.store',
                    'goods_out.create_independent',
                    'goods_out.store_independent',
                    'goods_out.bulk',
                    'goods_out.edit',
                    'goods_out.update',
                    'goods_out.destroy'
                ]) &&
                !in_array(Auth::user()->role, ['admin_logistic', 'super_admin'])
            ) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        })->only(['create', 'store', 'createIndependent', 'storeIndependent', 'bulkGoodsOut', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        // Tambahkan eager loading untuk relasi goodsIns
        $query = GoodsOut::with(['inventory', 'project', 'goodsIns', 'materialRequest']);

        // Apply filters
        if ($request->has('material') && $request->material !== null) {
            $query->where('inventory_id', $request->material);
        }

        if ($request->has('qty') && $request->qty !== null) {
            $query->where('quantity', $request->qty);
        }

        if ($request->has('project') && $request->project !== null) {
            $query->where('project_id', $request->project);
        }

        if ($request->has('requested_at') && $request->requested_at !== null) {
            $query->whereDate('created_at', $request->requested_at);
        }

        if ($request->has('requested_by') && $request->requested_by !== null) {
            $query->where('requested_by', $request->requested_by);
        }

        $goodsOuts = $query->orderBy('created_at', 'desc')->get();

        // Pass data for filters
        $materials = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $quantities = GoodsOut::select('quantity')->distinct()->pluck('quantity');
        $users = User::orderBy('username')->get();

        return view('goods_out.index', compact('goodsOuts', 'materials', 'projects', 'quantities', 'users'));
    }

    public function export(Request $request)
    {
        // Ambil filter dari request
        $material = $request->material;
        $qty = $request->qty;
        $project = $request->project;
        $requestedBy = $request->requested_by;
        $requestedAt = $request->requested_at;

        // Filter data berdasarkan request
        $query = GoodsOut::with('inventory', 'project');

        if ($material) {
            $query->where('inventory_id', $material);
        }

        if ($qty) {
            $query->where('quantity', $qty);
        }

        if ($project) {
            $query->where('project_id', $project);
        }

        if ($requestedBy) {
            $query->where('requested_by', $requestedBy);
        }

        if ($requestedAt) {
            $query->whereDate('created_at', $requestedAt);
        }

        $goodsOuts = $query->get();

        // Buat nama file dinamis
        $fileName = 'goods_out';
        if ($material) {
            $materialName = Inventory::find($material)->name ?? 'Unknown Material';
            $fileName .= '_material-' . str_replace(' ', '-', strtolower($materialName));
        }
        if ($qty) {
            $fileName .= '_qty-' . $qty;
        }
        if ($project) {
            $projectName = Project::find($project)->name ?? 'Unknown Project';
            $fileName .= '_project-' . str_replace(' ', '-', strtolower($projectName));
        }
        if ($requestedBy) {
            $fileName .= '_requested_by-' . strtolower($requestedBy);
        }
        if ($requestedAt) {
            $fileName .= '_proceed_at-' . $requestedAt;
        }
        $fileName .= '_' . now()->format('Y-m-d') . '.xlsx';

        // Ekspor data menggunakan kelas GoodsOutExport
        return Excel::download(new GoodsOutExport($goodsOuts), $fileName);
    }

    public function create($materialRequestId)
    {
        $materialRequest = MaterialRequest::with('inventory', 'project')->findOrFail($materialRequestId);
        $inventories = Inventory::orderBy('name')->get();
        return view('goods_out.create', compact('materialRequest', 'inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_request_id' => 'required|exists:material_requests,id',
            'quantity' => 'required|numeric|min:0.01',
            'remark' => 'nullable|string',
        ]);

        $materialRequest = MaterialRequest::findOrFail($request->material_request_id);
        $inventory = $materialRequest->inventory;

        // Validasi quantity
        $remainingQty = $materialRequest->qty - $materialRequest->processed_qty;
        if ($request->quantity > $remainingQty) {
            return back()->withInput()->with('error', 'Quantity cannot exceed the remaining requested quantity.');
        }

        // Validasi tambahan: Pastikan stok inventory tidak menjadi negatif
        if ($request->quantity > $inventory->quantity) {
            return back()->withInput()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Tambahkan ke processed_qty
        $materialRequest->processed_qty += $request->quantity;

        // Update status jika sudah selesai
        if ($materialRequest->processed_qty >= $materialRequest->qty) {
            $materialRequest->status = 'delivered';
        }

        $materialRequest->save();

        event(new \App\Events\MaterialRequestUpdated($materialRequest, 'status'));

        // Simpan Goods Out
        GoodsOut::create([
            'material_request_id' => $materialRequest->id,
            'inventory_id' => $inventory->id,
            'project_id' => $materialRequest->project_id,
            'requested_by' => $materialRequest->requested_by,
            'department' => $materialRequest->department,
            'quantity' => $request->quantity,
            'remark' => $request->remark,
        ]);

        // Kurangi stok inventory
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        MaterialUsageHelper::sync($inventory->id, $materialRequest->project_id);

        return redirect()->route('goods_out.index')->with('success', "Goods Out processed successfully.");
    }

    public function createIndependent()
    {
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('username')->get()->map(function ($user) {
            $user->department = match ($user->role) {
                'admin_mascot' => 'mascot',
                'admin_costume' => 'costume',
                'admin_logistic' => 'logistic',
                'admin_finance' => 'finance',
                'super_admin' => 'management',
                default => 'general',
            };
            return $user;
        });
        return view('goods_out.create_independent', compact('inventories', 'projects', 'users'));
    }

    public function storeIndependent(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'nullable|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|numeric|min:0.01',
            'remark' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($request->inventory_id);
        $user = User::findOrFail($request->user_id);

        // Tentukan department berdasarkan role user
        $department = match ($user->role) {
            'admin_mascot' => 'mascot',
            'admin_costume' => 'costume',
            'admin_logistic' => 'logistic',
            'admin_finance' => 'finance',
            'super_admin' => 'management',
            default => 'general',
        };

        // Validasi quantity
        if ($request->quantity > $inventory->quantity) {
            return back()->withInput()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Kurangi stok di inventory
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Simpan Goods Out
        GoodsOut::create([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'requested_by' => $user->username,
            'department' => $department, // Pastikan department diteruskan
            'quantity' => $request->quantity,
            'remark' => $request->remark,
        ]);

        // Sync Material Usage hanya jika ada project
        if ($request->filled('project_id')) {
            MaterialUsageHelper::sync($request->inventory_id, $request->project_id);
        }

        return redirect()->route('goods_out.index')->with('success', "Goods Out created successfully.");
    }

    public function bulkGoodsOut(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:material_requests,id',
        ]);

        $materialRequests = MaterialRequest::whereIn('id', $request->selected_ids)
            ->where('status', 'approved')
            ->get();

        $updatedRequests = [];
        foreach ($materialRequests as $materialRequest) {
            $inventory = $materialRequest->inventory;

            // Validasi stok
            if ($materialRequest->qty > $inventory->quantity) {
                return back()->with('error', "Insufficient stock for {$inventory->name}.");
            }

            // Kurangi stok inventory
            $inventory->quantity -= $materialRequest->qty;
            $inventory->save();

            // Buat Goods Out
            GoodsOut::create([
                'material_request_id' => $materialRequest->id,
                'inventory_id' => $inventory->id,
                'project_id' => $materialRequest->project_id,
                'requested_by' => $materialRequest->requested_by,
                'department' => $materialRequest->department,
                'quantity' => $materialRequest->qty,
                'remark' => 'Bulk Goods Out',
            ]);

            // Update status material request
            $materialRequest->update([
                'status' => 'delivered',
                'processed_qty' => $materialRequest->qty,
            ]);
            $updatedRequests[] = $materialRequest->fresh(['inventory', 'project']);

            MaterialUsageHelper::sync($inventory->id, $materialRequest->project_id);
        }

        // Broadcast real-time ke semua client
        if ($updatedRequests) {
            event(new \App\Events\MaterialRequestUpdated($updatedRequests, 'status'));
        }

        return redirect()->route('material_requests.index')->with('success', "Bulk Goods Out processed successfully.");
    }

    public function getDetails(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:goods_out,id',
        ]);

        $goodsOuts = GoodsOut::whereIn('id', $request->selected_ids)
            ->with('inventory')
            ->get()
            ->map(function ($goodsOut) {
                return [
                    'id' => $goodsOut->id,
                    'material_name' => $goodsOut->inventory->name,
                    'goods_out_quantity' => $goodsOut->quantity,
                ];
            });

        return response()->json($goodsOuts);
    }

    public function edit($id)
    {
        $goodsOut = GoodsOut::with('inventory', 'project')->findOrFail($id);
        $inventories = Inventory::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $users = User::all()->map(function ($user) {
            $user->department = match ($user->role) {
                'admin_mascot' => 'mascot',
                'admin_costume' => 'costume',
                'admin_logistic' => 'logistic',
                'admin_finance' => 'finance',
                'super_admin' => 'management',
                default => 'general',
            };
            return $user;
        });

        return view('goods_out.edit', compact('goodsOut', 'inventories', 'projects', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|numeric|min:0.01',
            'remark' => 'nullable|string',
        ]);

        $goodsOut = GoodsOut::findOrFail($id);
        if (!$goodsOut) {
            return redirect()->route('goods_out.index')->with('error', 'Goods Out not found.');
        }
        $inventory = Inventory::findOrFail($request->inventory_id);
        $materialRequest = $goodsOut->materialRequest;

        $user = User::findOrFail($request->user_id);

        // Kembalikan stok lama ke inventory
        $oldQuantity = $goodsOut->quantity;
        $inventory->quantity += $oldQuantity;

        // Kembalikan quantity lama ke Material Request
        if ($materialRequest) {
            $materialRequest->qty += $oldQuantity;
        }

        // Validasi quantity baru
        if ($request->quantity > ($inventory->quantity + $oldQuantity)) {
            return back()->with('error', 'Quantity cannot exceed the available inventory.');
        }

        // Kurangi stok dengan quantity baru
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        // Perbarui Material Request dengan quantity baru
        if ($materialRequest) {
            $materialRequest->qty -= $request->quantity;

            // Perbarui status jika quantity habis
            if ($materialRequest->qty == 0) {
                $materialRequest->status = 'delivered';
            } else {
                $materialRequest->status = 'approved';
            }

            $materialRequest->save();
        }

        // Perbarui Goods Out
        $goodsOut->update([
            'inventory_id' => $request->inventory_id,
            'project_id' => $request->project_id,
            'requested_by' => $user->username,
            'department' => match ($user->role) {
                'admin_mascot' => 'mascot',
                'admin_costume' => 'costume',
                'admin_logistic' => 'logistic',
                'admin_finance' => 'finance',
                'super_admin' => 'management',
                default => 'general',
            },
            'quantity' => $request->quantity,
            'remark' => $request->remark,
        ]);

        MaterialUsageHelper::sync($goodsOut->inventory_id, $goodsOut->project_id);

        return redirect()->route('goods_out.index')->with('success', "Goods Out updated successfully.");
    }

    public function restore($id)
    {
        $goodsOut = GoodsOut::withTrashed()->findOrFail($id);

        // Restore Goods Out
        $goodsOut->restore();

        // Kurangi stok di inventory
        $inventory = $goodsOut->inventory;
        if ($inventory) {
            $inventory->quantity -= $goodsOut->quantity;
            $inventory->save();
        }

        // Sinkronkan Material Usage
        MaterialUsageHelper::sync($goodsOut->inventory_id, $goodsOut->project_id);

        return redirect()->route('goods_out.index')->with('success', "Goods Out restored successfully.");
    }

    public function destroy($id)
    {
        $goodsOut = GoodsOut::findOrFail($id);

        // Cek apakah ada Goods In yang terkait
        if ($goodsOut->goodsIns()->exists()) {
            return redirect()->route('goods_out.index')->with('error', "Cannot delete Goods Out with related Goods In.");
        }

        // Kembalikan stok ke inventory
        $inventory = $goodsOut->inventory;
        $inventory->quantity += $goodsOut->quantity;
        $inventory->save();

        // Soft delete Goods Out
        $goodsOut->delete();

        MaterialUsageHelper::sync($goodsOut->inventory_id, $goodsOut->project_id);

        return redirect()->route('goods_out.index')->with('success', "Goods Out deleted successfully.");
    }
}
