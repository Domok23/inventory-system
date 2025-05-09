<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Currency;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InventoryImport;


class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin', 'admin_logistic'];
            if (!in_array(auth()->user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $inventories = Inventory::all();
        return view('inventory.index', compact('inventories'));
    }

    public function create()
    {
        $currencies = Currency::all(); // Ambil semua currency dari database
        $units = Unit::all(); // Ambil semua unit dari database
        return view('inventory.create', compact('currencies', 'units'));
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

            $inventory = new Inventory();
            $inventory->name = $row[0] ?? null;
            $inventory->quantity = $row[1] ?? 0;
            $inventory->unit = $row[2] ?? '-';
            $inventory->price = $row[3] ?? 0;
            $inventory->location = $row[4] ?? null;
            $inventory->save();

            // Generate QR
            $qrContent = $inventory->id . ' - ' . $inventory->name;
            $qrFileName = 'qr_' . uniqid() . '.svg';
            $qrImage = QrCode::format('svg')->size(200)->generate($qrContent);
            Storage::disk('public')->put('qrcodes/' . $qrFileName, $qrImage);

            $inventory->qrcode_path = 'qrcodes/' . $qrFileName;
            $inventory->save();
        }

        return redirect()->route('inventory.index')->with('success', 'Import berhasil.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'new_unit' => 'required_if:unit,__new__|nullable|string|max:255',
            'price' => 'required|numeric',
            'location' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $inventory = new Inventory;
        $inventory->name = $request->name;
        $inventory->quantity = $request->quantity;
        $inventory->unit = $request->unit;
        $inventory->price = $request->price;
        $inventory->currency_id = $request->currency_id;
        $inventory->location = $request->location;

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

        // Generate konten QR
        $qrContent = $inventory->id . ' - ' . $inventory->name;
        $qrFileName = 'qr_' . uniqid() . '.svg';

        // Generate QR sebagai SVG
        $qrImage = QrCode::format('svg')->size(200)->generate($qrContent);

        // Simpan file SVG ke storage
        Storage::disk('public')->put('qrcodes/' . $qrFileName, $qrImage);

        // Simpan path-nya ke database
        $inventory->qrcode_path = 'qrcodes/' . $qrFileName;
        $inventory->save(); // Simpan lagi untuk memperbarui path QR code

        return redirect()->route('inventory.index')->with('success', 'Inventory added successfully!');
    }

    public function storeQuick(Request $request)
    {
        Inventory::create([
            'name'     => $request->name,
            'quantity' => $request->quantity,
            'unit'     => $request->unit,
            'price'    => $request->price ?? 0, // kasih default 0 kalau tidak diisi
            'status'   => 'pending', // jika kamu ingin tandai status awal
        ]);

        return back()->with('success', 'Material added');
    }

    public function json()
    {
        return Inventory::select('id', 'name')->get(); // bisa juga pakai paginate/dataTables untuk ribuan data
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $currencies = Currency::all(); // Ambil semua currency dari database
        $units = Unit::all();
        return view('inventory.edit', compact('inventory', 'currencies', 'units'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'unit' => 'required|string',
            'new_unit' => 'required_if:unit,__new__|nullable|string|max:255',
            'price' => 'nullable|numeric',
            'currency_id' => 'required|exists:currencies,id',
            'location' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data inventory
        $inventory->name = $request->name;
        $inventory->quantity = $request->quantity;
        $inventory->unit = $request->unit;
        $inventory->price = $request->price;
        $inventory->currency_id = $request->currency_id;
        $inventory->location = $request->location;

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

        // Generate ulang QR code jika name berubah
        $qrContent = $inventory->id . ' - ' . $inventory->name;
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

        return redirect()->route('inventory.index')->with('success', 'Inventory updated successfully.');
    }


    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'Inventory deleted successfully.');
    }
}