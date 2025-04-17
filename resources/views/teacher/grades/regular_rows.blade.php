<tr>
    <td class="ps-4">{{ $index + 1 }}</td>
    <td>
        <div class="d-flex align-items-center">
            <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
            </div>
            <div>
                <div class="fw-bold">{{ $student->last_name }}, {{ $student->first_name }}</div>
                <div class="small text-muted">ID: {{ $student->student_id }}</div>
            </div>
        </div>
    </td>
    <td class="text-center">
        @if(count($writtenWorks) > 0)
            <div class="fw-bold">{{ number_format($writtenWorksAvg * $writtenWorkPercentage / 100, 1) }}%</div>
            <div class="small text-muted">{{ count($writtenWorks) }} assessments</div>
        @else
            <span class="badge bg-light text-muted">No data</span>
        @endif
    </td>
    <td class="text-center">
        @if(count($performanceTasks) > 0)
            <div class="fw-bold">{{ number_format($performanceTasksAvg * $performanceTaskPercentage / 100, 1) }}%</div>
            <div class="small text-muted">{{ count($performanceTasks) }} tasks</div>
        @else
            <span class="badge bg-light text-muted">No data</span>
        @endif
    </td>
    <td class="text-center">
        @if($quarterlyAssessment)
            <div class="fw-bold">{{ number_format($quarterlyScore * $quarterlyAssessmentPercentage / 100, 1) }}%</div>
            <div class="small text-muted">
                {{ $quarterlyAssessment->score }}/{{ $quarterlyAssessment->max_score }}
            </div>
        @else
            <span class="badge bg-light text-muted">No data</span>
        @endif
    </td>
    <td class="text-center">
        <div class="fw-bold">{{ number_format($finalGrade, 1) }}%</div>
        @php
            // Determine grade class for display
            $gradeClass = 'secondary';
            if ($finalGrade >= 90) {
                $gradeClass = 'success';
            } elseif ($finalGrade >= 80) {
                $gradeClass = 'primary';
            } elseif ($finalGrade >= 70) {
                $gradeClass = 'info';
            } elseif ($finalGrade >= 60) {
                $gradeClass = 'warning';
            } elseif ($finalGrade > 0) {
                $gradeClass = 'danger';
            }
        @endphp
        <span class="badge bg-{{ $gradeClass }} bg-opacity-25 text-{{ $gradeClass }} small">Initial</span>
    </td>
    <td class="text-center">
        @php
            // Get the selected transmutation table from request or session
            $selectedTableId = request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1));

            // Get the transmuted grade using the selected table
            $transmutedGrade = getTransmutedGrade($finalGrade, $selectedTableId);

            // Determine grade class based on transmuted grade
            $transmutedClass = 'secondary';
            if ($transmutedGrade >= 90) {
                $transmutedClass = 'success';
            } elseif ($transmutedGrade >= 80) {
                $transmutedClass = 'primary';
            } elseif ($transmutedGrade >= 75) {
                $transmutedClass = 'info';
            } elseif ($transmutedGrade > 0) {
                $transmutedClass = 'danger';
            }
        @endphp
        <div class="fw-bold fs-4">{{ $transmutedGrade }}</div>
        <span class="badge bg-{{ $transmutedClass }} small">Quarterly</span>
        <div class="mt-1 small text-muted d-none d-md-block">
            <small>{{ number_format($finalGrade, 1) }}% → {{ $transmutedGrade }}</small>
        </div>
    </td>
    <td class="text-center pe-4">
        <div class="btn-group">
            <a href="#" class="btn btn-sm btn-outline-primary"
               data-bs-toggle="modal"
               data-bs-target="#studentDetailsModal{{ $student->id }}"
               title="View Details">
                <i class="fas fa-eye"></i>
            </a>

            {{-- <button type="button"
                    class="btn btn-sm btn-outline-info"
                    onclick="printStudentReport({{ $student->id }})"
                    title="Print Report">
                <i class="fas fa-file-alt"></i>
            </button> --}}
        </div>
    </td>
</tr>