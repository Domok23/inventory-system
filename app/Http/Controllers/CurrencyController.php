<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin', 'admin_finance', 'admin_logistic'];
            if (!in_array(Auth::user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $currencies = Currency::all(); // Ambil semua currency dari database
        return view('currency.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->merge(['name' => trim($request->name)]);
        $request->validate([
            'name' => 'required|string|max:255',
            'exchange_rate' => 'nullable|numeric',
        ]);

        if ($request->ajax()) {
            // Cek currency dengan nama sama, case-insensitive, termasuk yang soft deleted
            $existing = Currency::withTrashed()
                ->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                ->first();
            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                    $existing->exchange_rate = $request->exchange_rate;
                    $existing->save();
                    return response()->json([
                        'success' => "Currency '{$existing->name}' restored successfully.",
                        'id' => $existing->id,
                        'name' => $existing->name,
                    ]);
                } else {
                    return response()->json([
                        'message' => "Currency '{$request->name}' already exists."
                    ], 422);
                }
            }
            // Jika belum ada, buat baru
            $currency = Currency::create($request->only('name', 'exchange_rate'));
            return response()->json([
                'success' => "Currency '{$currency->name}' added successfully.",
                'id' => $currency->id,
                'name' => $currency->name,
            ]);
        } else {
            // Untuk form biasa
            if ($request->id) {
                $currency = Currency::findOrFail($request->id);
                $currency->update($request->only('name', 'exchange_rate'));
                return back()->with('success', "Currency '{$currency->name}' updated successfully.");
            } else {
                $currency = Currency::create($request->only('name', 'exchange_rate'));
                return back()->with('success', "Currency '{$currency->name}' added successfully.");
            }
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name,' . $id,
            'exchange_rate' => 'nullable|numeric',
        ]);

        $currency = Currency::findOrFail($id);
        $currency->update($request->all());

        return back()->with('success', "Currency '{$currency->name}' updated successfully.");
    }

    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        $currencyName = $currency->name;
        $currency->delete();

        return back()->with('success', "Currency '{$currencyName}' deleted successfully.");
    }
}