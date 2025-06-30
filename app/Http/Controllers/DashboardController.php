<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;
use App\Models\Project;
use App\Models\MaterialRequest;

class DashboardController extends Controller
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
    }

    public function index()
    {
        $user = Auth::user(); // Mendapatkan data pengguna yang sedang login
        $inventoryCount = Inventory::count(); // Hitung total inventory
        $projectCount = Project::count(); // Hitung total proyek
        $pendingRequests = MaterialRequest::where('status', 'pending')->count(); // Hitung permintaan material yang pending

        return view('dashboard', compact('user', 'inventoryCount', 'projectCount', 'pendingRequests'));
    }
}
