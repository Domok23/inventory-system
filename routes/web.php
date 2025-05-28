<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\GoodsInController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\GoodsOutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MaterialUsageController;
use App\Http\Controllers\ProjectCostingController;
use App\Http\Controllers\MaterialRequestController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class);

    // Material Usage
    Route::get('/material-usage', [MaterialUsageController::class, 'index'])->name('material_usage.index');
    Route::delete('material-usage/{material_usage}', [MaterialUsageController::class, 'destroy'])->name('material_usage.destroy');
    Route::get('/material-usage/get-by-inventory', [MaterialUsageController::class, 'getByInventory'])->name('material_usage.get_by_inventory');

    // Inventory
    Route::get('/inventory/template', [InventoryController::class, 'downloadTemplate'])->name('inventory.template');
    Route::resource('inventory', InventoryController::class);
    Route::post('/inventory/import', [InventoryController::class, 'import'])->name('inventory.import');
    Route::get('/inventory/detail/{id}', [InventoryController::class, 'detail'])->name('inventory.detail');
    Route::post('/inventories/quick-add', [InventoryController::class, 'storeQuick'])->name('inventories.store.quick');
    Route::get('/inventories/json', [InventoryController::class, 'json'])->name('inventories.json');

    // QR
    Route::get('/qr-scan', function () {
        return view('qr-scan');
    })->name('qr.scan');
    Route::post('/process-qr', [InventoryController::class, 'processQr'])->name('qr.process');
    Route::get('/scan/{id}', [InventoryController::class, 'scanQr'])->name('inventory.scan');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/quick-add', [ProjectController::class, 'storeQuick'])->name('projects.store.quick');
    Route::get('/projects/json', [ProjectController::class, 'json'])->name('projects.json');

    // Material Requests
    Route::get('/material_requests', [MaterialRequestController::class, 'index'])->name('material_requests.index');
    Route::get('/material_requests/create', [MaterialRequestController::class, 'create'])->name('material_requests.create');
    Route::post('/material_requests', [MaterialRequestController::class, 'store'])->name('material_requests.store');
    Route::get('/material_requests/bulk_create', [MaterialRequestController::class, 'bulkCreate'])->name('material_requests.bulk_create');
    Route::post('/material_requests/bulk_store', [MaterialRequestController::class, 'bulkStore'])->name('material_requests.bulk_store');
    Route::get('/material_requests/{id}/edit', [MaterialRequestController::class, 'edit'])->name('material_requests.edit');
    Route::put('/material_requests/{id}', [MaterialRequestController::class, 'update'])->name('material_requests.update');
    Route::delete('/material_requests/{id}', [MaterialRequestController::class, 'destroy'])->name('material_requests.destroy');

    // Categories
    Route::post('/categories/quick-add', [CategoryController::class, 'storeQuick'])->name('categories.store');
    Route::get('/categories/json', [CategoryController::class, 'json'])->name('categories.json');

    // Currencies
    Route::resource('currencies', CurrencyController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('/currencies/{id}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');

    // Goods Out
    Route::resource('goods_out', GoodsOutController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/goods_out/create/{materialRequestId}', [GoodsOutController::class, 'create'])->name('goods_out.create');
    Route::get('/goods_out/create_independent', [GoodsOutController::class, 'createIndependent'])->name('goods_out.create_independent');
    Route::post('/goods_out/store_independent', [GoodsOutController::class, 'storeIndependent'])->name('goods_out.store_independent');
    Route::post('/material-requests/bulk-goods-out', [GoodsOutController::class, 'bulkGoodsOut'])->name('goods_out.bulk');

    // Goods In
    Route::get('/goods_in', [GoodsInController::class, 'index'])->name('goods_in.index');
    Route::get('/goods_in/create', [GoodsInController::class, 'create'])->name('goods_in.create');
    Route::post('/goods_in', [GoodsInController::class, 'store'])->name('goods_in.store');
    Route::get('/goods_in/create/{goods_out_id}', [GoodsInController::class, 'create'])->name('goods_in.create');
    Route::get('/goods_in/create_independent', [GoodsInController::class, 'createIndependent'])->name('goods_in.create_independent');
    Route::post('/goods_in/store_independent', [GoodsInController::class, 'storeIndependent'])->name('goods_in.store_independent');
    Route::get('goods_in/{goods_in}/edit', [GoodsInController::class, 'edit'])->name('goods_in.edit');
    Route::put('goods_in/{goods_in}', [GoodsInController::class, 'update'])->name('goods_in.update');
    Route::delete('goods_in/{goods_in}', [GoodsInController::class, 'destroy'])->name('goods_in.destroy');
    Route::post('/goods-out/bulk-goods-in', [GoodsInController::class, 'bulkGoodsIn'])->name('goods_in.bulk');

    // Costings
    Route::get('/costing-report', [ProjectCostingController::class, 'index'])->name('costing.report');
    Route::get('/costing-report/{project_id}', [ProjectCostingController::class, 'viewCosting'])->name('costing.view');
    Route::get('/costing-report/export/{project_id}', [ProjectCostingController::class, 'exportCosting'])->name('costing.export');
    Route::get('/costing-report', [ProjectCostingController::class, 'index'])->name('costing.report');

    //set inventory
    Route::post('/set-inventory', function (Request $request) {
        $request->session()->put('inventory_id', $request->input('inventory_id'));
        return redirect()->back();
    })->name('set_inventory');

    // Trash
    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/restore', [TrashController::class, 'restore'])->name('trash.restore');
    Route::delete('/trash/force-delete', [TrashController::class, 'forceDelete'])->name('trash.forceDelete');
    Route::post('/trash/bulk-action', [TrashController::class, 'bulkAction'])->name('trash.bulkAction');
    Route::get('/trash/{id}/details', [TrashController::class, 'show'])->name('trash.show');
});
