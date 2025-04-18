@props(['schoolDays' => 0, 'currentMonth' => null])

<div {{ $attributes->merge(['class' => 'school-day-indicator card border-0 shadow-sm']) }}>
    <div class="card-body p-3">
        <div class="d-flex align-items-center">
            <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                <i class="fas fa-calendar-day text-primary fa-lg"></i>
            </div>
            <div>
                <h6 class="mb-1 text-muted">School Days</h6>
                <div class="d-flex align-items-center">
                    <span class="h4 mb-0 fw-bold">{{ $schoolDays }}</span>
                    @if($currentMonth)
                        <span class="ms-2 text-muted small">in {{ $currentMonth }}</span>
                    @endif
                </div>
                <p class="mb-0 small text-muted">
                    <i class="fas fa-info-circle me-1"></i> Days with recorded attendance
                </p>
            </div>
        </div>
    </div>
</div>
