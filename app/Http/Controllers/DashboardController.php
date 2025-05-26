<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Inventory;
use App\Models\Project;
use App\Models\MaterialRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // Mendapatkan data pengguna yang sedang login
        $inventoryCount = Inventory::count(); // Hitung total inventory
        $projectCount = Project::count(); // Hitung total proyek
        $pendingRequests = MaterialRequest::where('status', 'pending')->count(); // Hitung permintaan material yang pending

        return view('dashboard', compact('user', 'inventoryCount', 'projectCount', 'pendingRequests'));
    }
}
