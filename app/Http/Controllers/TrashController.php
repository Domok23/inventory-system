<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Project;
use App\Models\MaterialUsage;
use App\Models\GoodsOut;
use App\Models\GoodsIn;
use App\Models\MaterialRequest;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin'];
            if (!in_array(Auth::user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }
    public function index()
    {
        return view('trash.index', [
            'inventories'      => Inventory::onlyTrashed()->get(),
            'projects'         => Project::onlyTrashed()->get(),
            'materialRequests' => MaterialRequest::onlyTrashed()->with(['inventory', 'project'])->get(),
            'goodsOuts'        => GoodsOut::onlyTrashed()->with(['inventory', 'project'])->get(),
            'goodsIns'         => GoodsIn::onlyTrashed()->with(['inventory', 'project'])->get(),
            'materialUsages'   => MaterialUsage::onlyTrashed()->with(['inventory', 'project'])->get(),
            'currencies'       => Currency::onlyTrashed()->get(),
            'users'            => User::onlyTrashed()->get(),
        ]);
    }

    public function restore(Request $request)
    {
        $model = $request->input('model');
        $id = $request->input('id');
        $modelClass = $this->getModelClass($model);
        if ($modelClass) {
            $modelClass::onlyTrashed()->findOrFail($id)->restore();
            return back()->with('success', ucfirst($model) . ' restored!');
        }
        return back()->with('error', 'Invalid model');
    }

    public function forceDelete(Request $request)
    {
        $model = $request->input('model');
        $id = $request->input('id');
        $modelClass = $this->getModelClass($model);

        if ($modelClass) {
            $item = $modelClass::onlyTrashed()->findOrFail($id);

            // Hapus file gambar
            if ($model === 'inventory') {
                // Hapus inventory image
                if ($item->img && Storage::disk('public')->exists($item->img)) {
                    Storage::disk('public')->delete($item->img);
                }
                // Hapus QR code file
                $qrCodePath = public_path('storage/qrcodes/' . $item->id . '.svg');
                if (file_exists($qrCodePath)) {
                    unlink($qrCodePath);
                }
            } elseif ($model === 'project') {
                // Hapus project image
                if ($item->img && Storage::disk('public')->exists($item->img)) {
                    Storage::disk('public')->delete($item->img);
                }
            }

            $item->forceDelete(); // Hapus permanen dari database
            return back()->with('success', ucfirst($model) . ' permanently deleted!');
        }

        return back()->with('error', 'Invalid model');
    }

    private function getModelClass($model)
    {
        return match ($model) {
            'inventory'        => Inventory::class,
            'project'          => Project::class,
            'material_request' => MaterialRequest::class,
            'goods_out'        => GoodsOut::class,
            'goods_in'         => GoodsIn::class,
            'material_usage'   => MaterialUsage::class,
            'currency'         => Currency::class,
            'user'             => User::class,
            default            => null,
        };
    }
    public function bulkAction(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        $modelMap = $request->input('model_map', []);
        $action = $request->input('action');

        if (!$ids || !$action) {
            return back()->with('error', 'No items selected or invalid action.');
        }

        foreach ($ids as $id) {
            $model = $modelMap[$id] ?? null;
            $modelClass = $this->getModelClass($model);
            if ($modelClass) {
                $item = $modelClass::onlyTrashed()->find($id);
                if ($item) {
                    if ($action === 'restore') {
                        $item->restore();
                    } elseif ($action === 'delete') {
                        $item->forceDelete();
                    }
                }
            }
        }

        return back()->with('success', 'Bulk action completed.');
    }
}