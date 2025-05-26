<?php
// filepath: c:\xampp\htdocs\inventory-system\app\Http\Middleware\SetInventory.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Support\Facades\Log;

class SetInventory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('inventory_id')) {
            $inventory = Inventory::first(); // Ambil inventory pertama
            if ($inventory) {
                $request->session()->put('inventory_id', $inventory->id);
                Log::info('SetInventory Middleware: inventory_id set to ' . $inventory->id);
            } else {
                Log::warning('SetInventory Middleware: No inventory found in database.');
            }
        } else {
            Log::info('SetInventory Middleware: inventory_id already in session: ' . $request->session()->get('inventory_id'));
        }

        return $next($request);
    }
}