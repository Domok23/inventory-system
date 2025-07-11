@forelse($projects as $project)
<tr>
    <td class="fw-semibold">{{ $project->name }}</td>
    <td>{{ ucfirst($project->department) }}</td>
    <td>
        <a href="{{ route('final_project_summary.show', $project) }}" class="btn btn-success btn-sm">
            <i class="bi bi-eye"></i> View Final Summary
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="3" class="text-center text-muted">No projects found.</td>
</tr>
@endforelse