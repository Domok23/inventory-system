<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin'];
            if (!in_array(auth()->user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:super_admin,admin_logistic,admin_mascot,admin_costume,admin_finance,admin_animatronic,general',
        ]);

        User::create([
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'User created');
    }

    public function show($id)
    {
        //
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'role' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->role = $request->role;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', "User updated successfully.");
    }

    public function destroy(User $user)
    {
        // Hindari menghapus super admin atau user aktif sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', "You cannot delete your own account.");
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', "User deleted successfully.");
    }
}