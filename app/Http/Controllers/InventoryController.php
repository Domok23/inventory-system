<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Inventory;
use App\Models\Unit;
use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Supplier;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Exports\InventoryExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\ImportInventoryTemplate;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin', 'admin_logistic', 'admin_mascot', 'admin_costume', 'admin_animatronic', 'admin_finance', 'general'];
            if (!in_array(Auth::user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });

        // Batasi akses untuk fitur tertentu hanya untuk super_admin dan admin_logistic
        $this->middleware(function ($request, $next) {
            $restrictedRoles = ['super_admin', 'admin_logistic'];
            if (in_array($request->route()->getName(), ['inventory.create', 'inventory.import', 'inventory.edit', 'inventory.destroy']) && !in_array(Auth::user()->role, $restrictedRoles)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        })->only(['create', 'import', 'edit', 'destroy']);
    }

    public function index(Request $request)
    {
        // Tambahkan eager loading untuk relasi category dan currency
        $query = Inventory::query()->with(['category', 'supplier', 'location', 'currency']);

        // Filter berdasarkan Category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan Currency
        if ($request->has('currency') && $request->currency) {
            $query->where('currency_id', $request->currency);
        }

        // Filter berdasarkan Supplier
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        // Filter berdasarkan Location
        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        $inventories = $query->orderBy('created_at', 'desc')->get();

        $categories = Category::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        // Generate QR codes dynamically
        foreach ($inventories as $inventory) {
            $qrCodePath = 'storage/qrcodes/' . $inventory->id . '.svg';
            $qrCodeFullPath = public_path($qrCodePath);
            if (!file_exists($qrCodeFullPath)) {
                QrCode::format('svg')
                    ->size(200)
                    ->generate(url('/inventory/detail/' . $inventory->id), $qrCodeFullPath);
            }
            $inventory->qr_code = asset($qrCodePath); // Simpan URL gambar QR Code
        }

        return view('inventory.index', compact('inventories', 'categories', 'currencies', 'suppliers', 'locations'));
    }

    public function export(Request $request)
    {
        // Ambil filter dari request
        $category = $request->category;
        $currency = $request->currency;
        $supplier = $request->supplier;
        $location = $request->location;

        // Filter data berdasarkan request
        $query = Inventory::query()->with(['category', 'supplier', 'location', 'currency']);

        if ($category) {
            $query->where('category_id', $category);
        }
        if ($currency) {
            $query->where('currency_id', $currency);
        }
        if ($supplier) {
            $query->where('supplier_id', $supplier);
        }
        if ($location) {
            $query->where('location_id', $location);
        }

        $inventories = $query->get();

        // Buat nama file dinamis
        $fileName = 'inventory';
        if ($category) {
            $categoryName = Category::find($category)->name ?? 'Unknown Category';
            $fileName .= '_category-' . str_replace(' ', '-', strtolower($categoryName));
        }
        if ($currency) {
            $currencyName = Currency::find($currency)->name ?? 'Unknown Currency';
            $fileName .= '_currency-' . str_replace(' ', '-', strtolower($currencyName));
        }
        if ($supplier) {
            $fileName .= '_supplier-' . str_replace(' ', '-', strtolower($supplier));
        }
        if ($location) {
            $fileName .= '_location-' . str_replace(' ', '-', strtolower($location));
        }
        $fileName .= '_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        // Ekspor data menggunakan kelas InventoryExport
        return Excel::download(new InventoryExport($inventories), $fileName);
    }

    public function create()
    {
        $currencies = Currency::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        return view('inventory.create', compact('currencies', 'units', 'categories', 'suppliers', 'locations'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'xls_file' => 'required|mimes:xls,xlsx',
        ]);

        $data = Excel::toArray([], $request->file('xls_file'))[0];

        $errors = []; // Array untuk menyimpan kesalahan
        $warnings = []; // Array untuk menyimpan peringatan
        $successCount = 0; // Counter untuk data yang berhasil diimpor

        foreach ($data as $index => $row) {
            if ($index === 0) {
                continue;
            } // Skip header row

            // Validasi nama inventory
            $inventoryName = $row[0] ?? null;
            if (!$inventoryName) {
                $errors[] = "Row <b>{$index}</b> Error: Inventory name is required.";
                continue; // Skip jika nama inventory kosong
            }

            // Validasi category
            $categoryName = $row[1] ?? null; // Ambil category dari kolom kedua
            $category = null;
            if ($categoryName) {
                $category = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();
                if (!$category) {
                    // Tambahkan kategori baru jika tidak ditemukan
                    $category = Category::create(['name' => $categoryName]);
                }
            }

            // Validasi unit
            $unitName = $row[3] ?? '-';
            $unit = Unit::firstOrCreate(['name' => $unitName]); // Tambahkan unit baru jika belum ada

            // Bersihkan data harga
            $price = str_replace([',', '$'], '', $row[4] ?? null);
            $price = is_numeric($price) ? $price : 0; // Jika harga kosong atau tidak valid, set ke 0

            // Validasi currency
            $currencyName = $row[5] ?? '-';
            $currency = Currency::where('name', $currencyName)->first();
            if (!$currency && $currencyName !== '-') {
                $errors[] = "Row <b>{$index}</b> Error: Invalid currency '{$currencyName}'.";
                continue; // Skip jika currency tidak valid
            }

            $supplierName = $row[6] ?? null;
            $supplier = $supplierName ? Supplier::firstOrCreate(['name' => $supplierName]) : null;

            $locationName = $row[7] ?? null;
            $location = $locationName ? Location::firstOrCreate(['name' => $locationName]) : null;

            $inventory = new Inventory();
            $inventory->name = $inventoryName;
            $inventory->category_id = $category ? $category->id : null; // Set category ID jika valid
            $inventory->quantity = is_numeric($row[2]) ? $row[2] : 0; // Jika quantity kosong, set ke 0
            $inventory->unit = $unit->name; // Gunakan nama unit yang sudah divalidasi
            $inventory->price = $price;
            $inventory->currency_id = $currency ? $currency->id : null; // Set currency ID jika valid
            $inventory->supplier_id = $supplier ? $supplier->id : null; // Set supplier ID jika valid
            $inventory->location_id = $location ? $location->id : null; // Set location ID jika valid
            $inventory->remark = $row[8] ?? null;

            // Cek jika inventory sudah ada
            $existingInventory = Inventory::where('name', $inventory->name)->first();
            if ($existingInventory) {
                $errors[] = "Row <b>{$index}</b> Error: Duplicate inventory <b>{$inventory->name}</b>.";
                continue; // Skip jika sudah ada
            }

            // Simpan inventory
            $inventory->save();
            $successCount++; // Tambahkan jumlah data yang berhasil diimpor

            // Tambahkan warning jika currency atau price kosong
            if (!$inventory->currency_id || !$inventory->price) {
                $warnings[] = "Price or Currency is empty for <b>{$inventory->name}</b>. Please update it as soon as possible, as it will affect the cost calculation!";
            }
        }

        // Kirim kesalahan ke session
        if (!empty($errors)) {
            session()->flash('error', implode('<br>', $errors));
        }

        // Kirim peringatan ke session
        if (!empty($warnings)) {
            session()->flash('warning', implode('<br>', $warnings));
        }

        // Kirim jumlah data yang berhasil dan gagal ke session
        $totalRows = count($data) - 1; // Total baris dikurangi header
        $failedCount = $totalRows - $successCount;

        $redirectData = [
            'success' => "<b>{$successCount}</b> rows imported successfully.",
        ];

        if ($failedCount > 0) {
            $redirectData['warning'] = "<b>{$failedCount}</b> rows failed to import.";
        }

        return redirect()->route('inventory.index')->with($redirectData);
    }

    public function downloadTemplate()
    {
        return Excel::download(new ImportInventoryTemplate(), 'inventory_template.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventories,name,NULL,id,deleted_at,NULL',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'new_unit' => 'required_if:unit,__new__|nullable|string|max:255',
            'price' => 'nullable|numeric',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'location_id' => 'nullable|exists:locations,id',
            'remark' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $inventory = new Inventory();
        $inventory->name = $request->name;
        $inventory->category_id = $request->category_id;
        $inventory->quantity = $request->quantity;
        $inventory->unit = $request->unit;
        $inventory->price = $request->price;
        $inventory->supplier_id = $request->supplier_id;
        $inventory->currency_id = $request->currency_id;
        $inventory->location_id = $request->location_id;
        $inventory->remark = $request->remark;

        // Simpan unit baru jika ada
        if ($request->unit === '__new__' && $request->new_unit) {
            $unit = Unit::firstOrCreate(['name' => $request->new_unit]);
            $inventory->unit = $unit->name; // Ganti nilai unit di $inventory
        }

        // Upload Image if exists
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('inventory_images', 'public');
            if ($imagePath) {
                $inventory->img = $imagePath;
            }
        }

        // Simpan inventory terlebih dahulu
        $inventory->save();

        // Buat pesan peringatan jika currency atau price kosong
        $warningMessage = null;
        if (!$inventory->currency_id || !$inventory->price) {
            $warningMessage = "Price or Currency is empty for <b>{$inventory->name}</b>. Please update it as soon as possible, as it will affect the cost calculation!";
        }

        return redirect()
            ->route('inventory.index')
            ->with([
                'success' => "Inventory <b>{$inventory->name}</b> added successfully!",
                'warning' => $warningMessage,
            ]);
    }

    public function storeQuick(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:inventories,name,NULL,id,deleted_at,NULL',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'remark' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()->first(),
                    ],
                    422,
                );
            }
            return back()->withErrors($validator)->withInput();
        }

        $unit = Unit::firstOrCreate(['name' => $request->unit]);

        $material = Inventory::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit' => $unit->name,
            'price' => $request->price ?? 0,
            'remark' => $request->remark ? $request->remark . ' <span style="color: orange;">(From Quick Add)</span>' : '<span style="color: orange;">(From Quick Add)</span>',
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'material' => $material]);
    }

    public function json(Request $request)
    {
        // return Inventory::select('id', 'name')->get();
        // Mengembalikan data inventory dalam format JSON
        $q = $request->q;
        $query = Inventory::select('id', 'name');
        if ($q) {
            // Escape karakter khusus untuk LIKE query
            $escapedQ = addcslashes($q, '%_\\');
            $query->where('name', 'like', '%' . $escapedQ . '%');
        }
        return response()->json($query->get());
        // bisa juga pakai paginate/dataTables untuk ribuan data
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $currencies = Currency::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        return view('inventory.edit', compact('inventory', 'currencies', 'units', 'categories', 'suppliers', 'locations'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventories,name,' . $inventory->id . ',id,deleted_at,NULL',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'new_unit' => 'required_if:unit,__new__|nullable|string|max:255',
            'currency_id' => 'nullable|exists:currencies,id',
            'price' => 'nullable|numeric',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'location_id' => 'nullable|exists:locations,id',
            'remark' => 'nullable|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $inventory->update(
            array_merge($validated, [
                'remark' => $validated['remark'], // Simpan remark asli tanpa tag tambahan
            ]),
        );

        // Update data inventory
        $inventory->name = $request->name;
        $inventory->category_id = $request->category_id;
        $inventory->quantity = $request->quantity;
        $inventory->unit = $request->unit;
        $inventory->currency_id = $request->currency_id;
        $inventory->price = $request->price;
        $inventory->supplier_id = $request->supplier_id;
        $inventory->location_id = $request->location_id;
        $inventory->remark = $request->remark;

        // Simpan unit baru jika ada
        if ($request->unit === '__new__' && $request->new_unit) {
            $unit = Unit::firstOrCreate(['name' => $request->new_unit]);
            $inventory->unit = $unit->name;
        } else {
            $inventory->unit = $request->unit;
        }

        // Upload image jika ada
        if ($request->hasFile('img')) {
            // Hapus gambar lama jika ada
            if ($inventory->img && Storage::disk('public')->exists($inventory->img)) {
                Storage::disk('public')->delete($inventory->img);
            }
            // Simpan gambar baru
            $imgPath = $request->file('img')->store('inventory_images', 'public');
            $inventory->img = $imgPath;
        }

        $inventory->save();

        // Buat pesan peringatan jika currency atau price kosong
        $warningMessage = null;
        if (!$inventory->currency_id || !$inventory->price) {
            $warningMessage = "Price or Currency is empty for <b>{$inventory->name}</b>. Please update it as soon as possible, as it will affect the cost calculation!";
        }

        return redirect()
            ->route('inventory.index')
            ->with([
                'success' => "Inventory <b>{$inventory->name}</b> edited successfully!",
                'warning' => $warningMessage,
            ]);
    }

    public function detail($id)
    {
        $inventory = Inventory::findOrFail($id);
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('username')->get();

        return view('inventory.detail', compact('inventory', 'projects', 'users'));
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()
            ->route('inventory.index')
            ->with('success', "Inventory <b>{$inventory->name}</b> deleted successfully.");
    }
}
