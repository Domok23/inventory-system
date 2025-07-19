<?php

namespace App\Http\Controllers;

use App\Models\Timing;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class TimingController extends Controller
{
    public function index(Request $request)
    {
        $timings = Timing::with(['project.department', 'employee'])
            ->orderByDesc('created_at')
            ->get();

        $projects = Project::with('department')->orderBy('name')->get();
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $employees = Employee::orderBy('name')->get();

        return view('timings.index', compact('timings', 'projects', 'departments', 'employees'));
    }

    public function ajaxSearch(Request $request)
    {
        $query = Timing::with(['project.department', 'employee']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('step', 'like', '%' . $request->search . '%')->orWhere('remarks', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('department')) {
            $query->whereHas('project.department', function ($q) use ($request) {
                $q->where('name', $request->department);
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $timings = $query->orderByDesc('tanggal')->get();

        try {
            $html = view('timings.timing_table', compact('timings'))->render();
            return response()->json([
                'html' => $html,
                'count' => $timings->count(),
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'html' => '<tr class="no-data-row"><td colspan="11" class="text-center text-muted py-4"><i class="bi bi-exclamation-triangle"></i> Error loading data</td></tr>',
                    'count' => 0,
                    'success' => false,
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function create()
    {
        $projects = Project::with(['parts', 'department'])->get();

        // HANYA ambil employee yang statusnya 'active'
        $employees = Employee::where('status', 'active')->orderBy('name')->get();

        $departments = Department::orderBy('name')->pluck('name', 'id');
        return view('timings.create', compact('projects', 'employees', 'departments'));
    }

    public function storeMultiple(Request $request)
    {
        $data = $request->validate([
            'timings' => 'required|array',
            'timings.*.tanggal' => 'required|date',
            'timings.*.project_id' => 'required|exists:projects,id',
            'timings.*.step' => 'required',
            'timings.*.parts' => 'nullable|string',
            'timings.*.employee_id' => 'required|exists:employees,id',
            'timings.*.start_time' => 'required',
            'timings.*.end_time' => 'required',
            'timings.*.output_qty' => 'required|numeric',
            'timings.*.status' => 'required|in:complete,on progress,pending',
            'timings.*.remarks' => 'nullable',
        ]);

        // Validasi tambahan: pastikan employee yang dipilih statusnya active
        foreach ($data['timings'] as $idx => $timing) {
            $employee = Employee::find($timing['employee_id']);
            if (!$employee || $employee->status !== 'active') {
                return back()
                    ->withErrors([
                        "timings.$idx.employee_id" => 'Selected employee is not active or does not exist.',
                    ])
                    ->withInput();
            }
        }

        $projectsWithParts = Project::has('parts')->pluck('id')->toArray();
        $errors = [];
        foreach ($data['timings'] as $idx => $timing) {
            if (in_array($timing['project_id'], $projectsWithParts)) {
                if (empty($timing['parts'])) {
                    $projectName = Project::find($timing['project_id'])->name ?? 'Unknown';
                    $errors["timings.$idx.parts"] = "Part is required for project: <b>$projectName</b>";
                }
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Jika semua valid, baru insert ke database
        foreach ($data['timings'] as &$timing) {
            if (!in_array($timing['project_id'], $projectsWithParts)) {
                $timing['parts'] = 'No Part';
            }
            Timing::create($timing);
        }

        return redirect()->route('timings.index')->with('success', 'All timing data is saved successfully.');
    }

    public function show(Timing $timing)
    {
        return view('timings.show', compact('timing'));
    }
}
