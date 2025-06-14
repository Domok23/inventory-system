<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin', 'admin_finance'];
            if (!in_array(auth()->user()->role, $rolesAllowed)) {
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
        $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name',
            'exchange_rate' => 'nullable|numeric',
        ]);

        if ($request->ajax()) {
            // Untuk quick add via AJAX
            if ($request->id) {
                $currency = Currency::findOrFail($request->id);
                $currency->update($request->only('name', 'exchange_rate'));
                return response()->json(['success' => "Currency '{$currency->name}' updated successfully."]);
            } else {
                $currency = Currency::create($request->only('name', 'exchange_rate'));
                return response()->json(['success' => "Currency '{$currency->name}' added successfully."]);
            }
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
