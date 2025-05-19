<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\GoodsIn;
use App\Models\GoodsOut;
use App\Models\Project;
use App\Models\User;
use App\Models\MaterialUsage;
use App\Models\MaterialRequest;
use App\Models\Currency;

class TrashController extends Controller
{
    public function index()
    {
        return view('trash.index', [
            'inventories'      => Inventory::onlyTrashed()->get(),
            'goodsIns'         => GoodsIn::onlyTrashed()->get(),
            'goodsOuts'        => GoodsOut::onlyTrashed()->get(),
            'projects'         => Project::onlyTrashed()->get(),
            'users'            => User::onlyTrashed()->get(),
            'materialUsages'   => MaterialUsage::onlyTrashed()->get(),
            'materialRequests' => MaterialRequest::onlyTrashed()->get(),
            'currencies'       => Currency::onlyTrashed()->get(),
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
            $modelClass::onlyTrashed()->findOrFail($id)->forceDelete();
            return back()->with('success', ucfirst($model) . ' permanently deleted!');
        }
        return back()->with('error', 'Invalid model');
    }

    private function getModelClass($model)
    {
        return match ($model) {
            'inventory'        => Inventory::class,
            'goods_in'         => GoodsIn::class,
            'goods_out'        => GoodsOut::class,
            'project'          => Project::class,
            'user'             => User::class,
            'material_usage'   => MaterialUsage::class,
            'material_request' => MaterialRequest::class,
            'currency'         => Currency::class,
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
