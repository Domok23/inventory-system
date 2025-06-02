<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $rolesAllowed = ['super_admin', 'admin_mascot', 'admin_costume'];
            if (!in_array(auth()->user()->role, $rolesAllowed)) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Project::query();

        // Apply filters
        if ($request->has('quantity') && $request->quantity !== null) {
            $query->where('qty', $request->quantity);
        }

        if ($request->has('department') && $request->department !== null) {
            $query->where('department', $request->department);
        }

        $projects = $query->latest()->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'qty' => 'required|integer|min:1',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deadline' => 'nullable|date',
            'department' => 'required|in:mascot,costume,mascot&costume,animatronic,plustoys,it,facility,bag'
        ]);

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('projects', 'public');
        }

        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function storeQuick(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'qty' => 'nullable|numeric|min:0',
            'department' => 'required|string',
        ]);

        $project = Project::create([
            'name'       => $request->name,
            'qty'        => $request->qty,
            'department' => $request->department,
        ]);

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'project' => $project]);
        }

        // Jika bukan AJAX, redirect biasa
        return back()->with('success', 'Project added');
    }

    public function json()
    {
        return Project::select('id', 'name')->get(); // bisa juga pakai paginate/dataTables untuk ribuan data
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'qty' => 'required|integer|min:1',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deadline' => 'nullable|date',
            'department' => 'required|in:mascot,costume,mascot&costume,animatronic,plustoys,it,facility,bag'
        ]);

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted.');
    }
}
