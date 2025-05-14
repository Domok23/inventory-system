<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\GoodsOutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MaterialRequestController;


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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::resource('inventory', InventoryController::class)->middleware('auth');
Route::post('/inventory/import', [InventoryController::class, 'import'])->middleware('auth')->name('inventory.import');
Route::get('/inventory/scan/{id}', [InventoryController::class, 'scan'])->name('inventory.scan');
Route::get('/qr-scan', function () {
    return view('qr-scan');
})->middleware('auth')->name('qr.scan');
Route::post('/process-qr', [InventoryController::class, 'processQr'])->middleware('auth')->name('qr.process');

Route::resource('projects', ProjectController::class)->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/material_requests', [MaterialRequestController::class, 'index'])->name('material_requests.index');
    Route::get('/material_requests/create', [MaterialRequestController::class, 'create'])->name('material_requests.create');
    Route::post('/material_requests', [MaterialRequestController::class, 'store'])->name('material_requests.store');
    Route::get('/material_requests/bulk_create', [MaterialRequestController::class, 'bulkCreate'])->name('material_requests.bulk_create'); // Tambahkan ini
    Route::post('/material_requests/bulk_store', [MaterialRequestController::class, 'bulkStore'])->name('material_requests.bulk_store');
    Route::get('/material_requests/{id}/edit', [MaterialRequestController::class, 'edit'])->name('material_requests.edit');
    Route::put('/material_requests/{id}', [MaterialRequestController::class, 'update'])->name('material_requests.update');
    Route::delete('/material_requests/{id}', [MaterialRequestController::class, 'destroy'])->name('material_requests.destroy');
});

Route::post('/inventories/quick-add', [InventoryController::class, 'storeQuick'])->name('inventories.store.quick');
Route::post('/projects/quick-add', [ProjectController::class, 'storeQuick'])->name('projects.store.quick');

Route::get('/inventories/json', [InventoryController::class, 'json'])->name('inventories.json');
Route::get('/projects/json', [ProjectController::class, 'json'])->name('projects.json');

Route::middleware(['auth'])->group(function () {
    Route::resource('currencies', CurrencyController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('/currencies/{id}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('goods_out', GoodsOutController::class)->only(['index', 'create', 'store']);
    Route::get('/goods_out/create/{materialRequestId}', [GoodsOutController::class, 'create'])->name('goods_out.create');
});
