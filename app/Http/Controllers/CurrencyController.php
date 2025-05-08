<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all(); // Ambil semua currency dari database
        return view('currency.index', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'exchange_rate' => 'nullable|numeric',
        ]);

        if ($request->id) {
            $currency = Currency::findOrFail($request->id);
            $currency->update($request->only('name', 'exchange_rate'));
        } else {
            Currency::create($request->only('name', 'exchange_rate'));
        }

        return back()->with('success', 'Currency saved successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'exchange_rate' => 'nullable|numeric',
        ]);

        $currency = Currency::findOrFail($id);
        $currency->update($request->all());

        return back()->with('success', 'Currency updated successfully.');
    }

    public function destroy($id)
    {
        Currency::findOrFail($id)->delete();

        return back()->with('success', 'Currency deleted successfully.');
    }
}
