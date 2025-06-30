<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectExport;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    public function export(Request $request)
    {
        // Ambil filter dari request
        $quantity = $request->quantity;
        $department = $request->department;

        // Filter data berdasarkan request
        $query = Project::query();

        if ($quantity) {
            $query->where('qty', $quantity);
        }

        if ($department) {
            $query->where('department', $department);
        }

        $projects = $query->get();

        // Buat nama file dinamis
        $fileName = 'projects';
        if ($quantity) {
            $fileName .= '_quantity-' . $quantity;
        }
        if ($department) {
            $fileName .= '_department-' . str_replace('&', 'and', strtolower($department));
        }
        $fileName .= '_' . now()->format('Y-m-d') . '.xlsx';

        // Ekspor data menggunakan kelas ProjectExport
        return Excel::download(new ProjectExport($projects), $fileName);
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
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date',
            'department' => 'required|in:mascot,costume,mascot&costume,animatronic,plustoys,it,facility,bag'
        ]);

        // Validasi: start_date tidak boleh melebihi deadline
        if ($request->start_date && $request->deadline && $request->start_date > $request->deadline) {
            return back()->withErrors(['start_date' => 'Start Date cannot be later than Deadline.'])->withInput();
        }

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('projects', 'public');
        }

        $project = Project::create(array_merge($validated, [
            'created_by' => Auth::user()->username, // Simpan username pembuat
        ]));

        return redirect()->route('projects.index')->with('success', "Project '{$project->name}' added successfully!");
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
            'created_by' => Auth::user()->username,
        ]);

        // Jika request AJAX, kembalikan JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'project' => $project]);
        }

        // Jika bukan AJAX, redirect biasa
        return back()->with('success', 'Project added successfully!');
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
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date',
            'department' => 'required|in:mascot,costume,mascot&costume,animatronic,plustoys,it,facility,bag'
        ]);

        // Validasi: start_date tidak boleh melebihi deadline
        if ($request->start_date && $request->deadline && $request->start_date > $request->deadline) {
            return back()->withErrors(['start_date' => 'Start Date cannot be later than Deadline.'])->withInput();
        }

        if ($request->hasFile('img')) {
            $validated['img'] = $request->file('img')->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', "Project '{$project->name}' updated successfully!");
    }

    public function destroy(Project $project)
    {
        // Validasi: Hanya pembuat proyek atau super_admin yang dapat menghapus
        if (Auth::user()->username !== $project->created_by && Auth::user()->role !== 'super_admin') {
            return redirect()->route('projects.index')->with('error', "You are not authorized to delete this project.");
        }

        $projectName = $project->name;
        $project->delete();

        return redirect()->route('projects.index')->with('success', "Project '{$projectName}' deleted successfully!");
    }
}
