<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Unit;
use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Imports\InventoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Exports\InventoryTemplateExport;
use App\Exports\InventoryExport;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin', 'admin_logistic', 'admin_mascot', 'admin_costume', 'admin_animatronic', 'admin_finance', 'general'];
            if (!in_array(auth()->user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Tambahkan eager loading untuk relasi category dan currency
        $query = Inventory::with(['category', 'currency']);

        // Filter berdasarkan Category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan Currency
        if ($request->has('currency') && $request->currency) {
            $query->where('currency_id', $request->currency);
        }

        // Filter berdasarkan Location
        if ($request->has('location') && $request->location) {
            $query->where('location', $request->location);
        }

        $inventories = $query->orderBy('created_at', 'desc')->get();
        $categories = Category::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        $locations = Inventory::select('location')->distinct()->whereNotNull('location')->orderBy('location')->pluck('location');

        return view('inventory.index', compact('inventories', 'categories', 'currencies', 'locations'));
    }

    public function export(Request $request)
    {
        // Ambil filter dari request
        $category = $request->category;
        $currency = $request->currency;
        $location = $request->location;

        // Filter data berdasarkan request
        $query = Inventory::query();

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($currency) {
            $query->where('currency_id', $currency);
        }

        if ($location) {
            $query->where('location', $location);
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
        if ($location) {
            $fileName .= '_location-' . str_replace(' ', '-', strtolower($location));
        }
        $fileName .= '_' . now()->format('Y-m-d') . '.xlsx';

        // Ekspor data menggunakan kelas InventoryExport
        return Excel::download(new InventoryExport($inventories), $fileName);
    }

    public function create()
    {
        $currencies = Currency::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('inventory.create', compact('currencies', 'units', 'categories'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'xls_file' => 'required|mimes:xls,xlsx',
        ]);

        $path = $request->file('xls_file')->getRealPath();
        $data = Excel::toArray([], $path)[0];

        foreach ($data as $index => $row) {
            if ($index === 0) continue; // Skip header row

            $currency = Currency::where('name', $row[3] ?? '')->first(); // Cari currency berdasarkan nama
            if (!$currency) {
                continue; // Skip jika currency tidak ditemukan
            }

            $inventory = new Inventory();
            $inventory->name = $row[0] ?? null;
            $inventory->quantity = $row[1] ?? 0;
            $inventory->unit = $row[2] ?? '-';
            $inventory->currency_id = $currency->id;
            $inventory->price = $row[3] ?? 0;
            $inventory->location = $row[4] ?? null;
            $inventory->save(); // Simpan data inventory terlebih dahulu untuk mendapatkan ID

            if (!$inventory->name || !$inventory->quantity || !$inventory->unit || !$inventory->price) {
                continue; // Skip invalid rows
            }

            // Cek jika inventory sudah ada
            $existingInventory = Inventory::where('name', $inventory->name)->first();
            if ($existingInventory) {
                continue; // Skip jika sudah ada
            }

            // **Point 5: Generate QR Code**
            $qrContent = route('inventory.scan', ['id' => $inventory->id]); // Gunakan URL lengkap
            $qrFileName = 'qr_' . uniqid() . '.svg';
            $qrImage = QrCode::format('svg')->size(200)->generate($qrContent);
            Storage::disk('public')->put('qrcodes/' . $qrFileName, $qrImage);

            // Simpan path QR Code ke database
            $inventory->qrcode_path = 'qrcodes/' . $qrFileName;
            $inventory->save();

            // Simpan unit baru jika belum ada
            $inventory->unitModel = Unit::firstOrCreate(['name' => $inventory->unit]);

            // Simpan inventory
            Inventory::create([
                'name' => $inventory->name,
                'quantity' => $inventory->quantity,
                'unit' => $inventory->unitModel->name,
                'currency_id' => $inventory->currency_id,
                'price' => $inventory->price,
                'location' => $inventory->location,
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Inventory imported successfully!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new InventoryTemplateExport, 'inventory_template.xlsx');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventories,name',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'new_unit' => 'required_if:unit,__new__|nullable|string|max:255',
            'price' => 'nullable|numeric',
            'location' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        $inventory = new Inventory;
        $inventory->name = $request->name;
        $inventory->quantity = $request->quantity;
        $inventory->unit = $request->unit;
        $inventory->price = $request->price;
        $inventory->currency_id = $request->currency_id;
        $inventory->location = $request->location;
        $inventory->category_id = $request->category_id;

        // Simpan unit baru jika ada
        if ($request->unit === '__new__' && $request->new_unit) {
            $unit = Unit::firstOrCreate(['name' => $request->new_unit]);
            $inventory->unit = $unit->name; // Ganti nilai unit di $inventory
        }

        // Upload Image if exists
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('images', 'public');
            if ($imagePath) {
                $inventory->img = $imagePath;
            }
        }

        // Simpan inventory terlebih dahulu
        $inventory->save();

        // **Point 5: Generate QR Code**
        $qrContent = route('inventory.scan', ['id' => $inventory->id]); // URL lengkap
        $qrFileName = 'qr_' . uniqid() . '.svg';
        $qrImage = QrCode::format('svg')->size(200)->generate($qrContent);
        Storage::disk('public')->put('qrcodes/' . $qrFileName, $qrImage);

        // Simpan path-nya ke database
        $inventory->qrcode_path = 'qrcodes/' . $qrFileName;
        $inventory->save(); // Simpan lagi untuk memperbarui path QR code

        // Buat pesan peringatan jika currency atau price kosong
        $warningMessage = null;
        if (!$inventory->currency_id || !$inventory->price) {
            $warningMessage = "Currency or Price is empty for '{$inventory->name}'. Please update it as soon as possible!";
        }

        return redirect()->route('inventory.index')->with([
            'success' => 'Inventory added successfully!',
            'warning' => $warningMessage,
        ]);
    }

    public function storeQuick(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventories,name',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
        ]);
        $unit = Unit::firstOrCreate(['name' => $request->unit]);
        $material = Inventory::create([
            'name'     => $request->name,
            'quantity' => $request->quantity,
            'unit'     => $unit->name,
            'price'    => $request->price ?? 0,
            'status'   => 'pending',
        ]);
        return response()->json(['success' => true, 'material' => $material]);
    }

    public function json()
    {
        return Inventory::select('id', 'name')->get(); // bisa juga pakai paginate/dataTables untuk ribuan data
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $currencies = Currency::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('inventory.edit', compact('inventory', 'currencies', 'units', 'categories'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventories,name,' . $inventory->id,
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'new_unit' => 'required_if:unit,__new__|nullable|string|max:255',
            'price' => 'nullable|numeric',
            'currency_id' => 'nullable|exists:currencies,id',
            'location' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Update data inventory
        $inventory->name = $request->name;
        $inventory->quantity = $request->quantity;
        $inventory->unit = $request->unit;
        $inventory->price = $request->price;
        $inventory->currency_id = $request->currency_id;
        $inventory->location = $request->location;
        $inventory->category_id = $request->category_id;

        // Simpan unit baru jika ada
        if ($request->unit === '__new__' && $request->new_unit) {
            $unit = Unit::firstOrCreate(['name' => $request->new_unit]);
            $inventory->unit = $unit->name; // Ganti nilai unit di inventory
        } else {
            $inventory->unit = $request->unit;
        }

        // Upload image jika ada
        if ($request->hasFile('img')) {
            $imgPath = $request->file('img')->store('inventory_images', 'public');
            $inventory->img = $imgPath;
        }

        // Generate ulang QR code dengan URL detail material
        $qrContent = route('inventory.scan', ['id' => $inventory->id]);
        $qrFileName = 'qr_' . uniqid() . '.svg';
        $qrImage = QrCode::format('svg')->size(200)->generate($qrContent);
        Storage::disk('public')->put('qrcodes/' . $qrFileName, $qrImage);

        // Hapus QR code lama jika ada
        if ($inventory->qrcode_path && Storage::disk('public')->exists($inventory->qrcode_path)) {
            Storage::disk('public')->delete($inventory->qrcode_path);
        }

        // Simpan QR path baru
        $inventory->qrcode_path = 'qrcodes/' . $qrFileName;

        $inventory->save();

        // Buat pesan peringatan jika currency atau price kosong
        $warningMessage = null;
        if (!$inventory->currency_id || !$inventory->price) {
            $warningMessage = "Currency or Price is empty for '{$inventory->name}'. Please update it as soon as possible!";
        }

        return redirect()->route('inventory.index')->with([
            'success' => 'Inventory added successfully!',
            'warning' => $warningMessage,
        ]);
    }

    public function processQr(Request $request)
    {
        $qrData = $request->input('qrData');

        // Cari inventory berdasarkan ID
        $inventoryItem = Inventory::find($qrData);

        if ($inventoryItem) {
            return response()->json([
                'success' => true,
                'url' => route('inventory.detail', ['id' => $inventoryItem->id])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ]);
    }

    public function detail($id)
    {
        $inventory = Inventory::findOrFail($id);
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('username')->get();

        return view('inventory.detail', compact('inventory', 'projects', 'users'));
    }

    public function scanQr($id)
    {
        // Redirect ke detail inventory di domain saat ini
        return redirect()->route('inventory.detail', ['id' => $id]);
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Inventory deleted successfully.');
    }
}
