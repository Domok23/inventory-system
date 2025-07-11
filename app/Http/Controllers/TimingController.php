<?php

namespace App\Http\Controllers;

use App\Models\Timing;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;
class TimingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $timings = Timing::with(['project', 'employee'])
        // ...tambahkan filter jika ada...
        ->get();

    $projects = Project::orderBy('name')->get();
    $departments = Project::select('department')->distinct()->pluck('department');
    $employees = Employee::orderBy('name')->get();

    return view('timings.index', compact('timings', 'projects', 'departments', 'employees'));
}

    public function create()
    {
        $projects = Project::with('parts')->get();
        $employees = Employee::all();
        $categories = Project::select('department')->distinct()->pluck('department');
        return view('timings.create', compact('projects', 'employees', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'project_id' => 'required|exists:projects,id',
            'category' => 'required',
            'step' => 'required',
            'parts' => 'required',
            'employee_id' => 'required|exists:employees,id',
            'start_time' => 'required',
            'end_time' => 'required',
            'output_qty' => 'required|numeric',
            'status' => 'required|in:complete,on progress,not started',
            'remarks' => 'nullable',
        ]);
        Timing::create($request->all());
        return redirect()->route('timings.index')->with('success', 'Data timing berhasil ditambahkan.');
    }

    public function show(Timing $timing)
    {
        return view('timings.show', compact('timing'));
    }

public function storeMultiple(Request $request)
{
    $data = $request->validate([
        'timings' => 'required|array',
        'timings.*.tanggal' => 'required|date',
        'timings.*.project_id' => 'required|exists:projects,id',
        'timings.*.category' => 'required',
        'timings.*.step' => 'required',
        'timings.*.parts' => 'required',
        'timings.*.employee_id' => 'required|exists:employees,id',
        'timings.*.start_time' => 'required',
        'timings.*.end_time' => 'required',
        'timings.*.output_qty' => 'required|numeric',
        'timings.*.status' => 'required|in:complete,on progress,not started',
        'timings.*.remarks' => 'nullable',
    ]);
    foreach ($data['timings'] as $timing) {
        \App\Models\Timing::create($timing);
    }
    return redirect()->route('timings.index')->with('success', 'Semua data timing berhasil disimpan.');
}

public function ajaxSearch(Request $request)
{
    $query = \App\Models\Timing::with(['project', 'employee']);

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('step', 'like', '%'.$request->search.'%')
              ->orWhere('remarks', 'like', '%'.$request->search.'%');
        });
    }
    if ($request->filled('project_id')) {
        $query->where('project_id', $request->project_id);
    }
    if ($request->filled('department')) {
        $query->where('category', $request->department);
    }
    if ($request->filled('employee_id')) {
        $query->where('employee_id', $request->employee_id);
    }

    $timings = $query->orderByDesc('tanggal')->get();

    $html = view('timings._timing_table', compact('timings'))->render();
    return response()->json(['html' => $html]);
}
    
}