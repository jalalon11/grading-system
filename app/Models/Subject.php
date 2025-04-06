<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'grade_level',
        'description',
        'school_id',
        'is_active',
        'parent_subject_id',
        'is_component',
        'component_weight',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_component' => 'boolean',
        'component_weight' => 'decimal:2',
    ];

    /**
     * Get the school that owns the subject.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the sections for this subject via the pivot table.
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class)
            ->withPivot('teacher_id')
            ->withTimestamps();
    }

    /**
     * Get the teachers assigned to this subject via the pivot table.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'section_subject', 'subject_id', 'teacher_id')
            ->withPivot('section_id')
            ->withTimestamps();
    }

    /**
     * Get grade configuration for this subject.
     */
    public function gradeConfiguration()
    {
        return $this->hasOne(GradeConfiguration::class);
    }

    /**
     * Get grades for this subject.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get parent subject for this component.
     */
    public function parentSubject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'parent_subject_id');
    }

    /**
     * Get component subjects for this parent subject.
     */
    public function components(): HasMany
    {
        return $this->hasMany(Subject::class, 'parent_subject_id');
    }

    /**
     * Check if this is a MAPEH subject
     */
    public function getIsMAPEHAttribute(): bool
    {
        // Check if this subject has component subjects that match MAPEH components
        if ($this->is_component) {
            return false;
        }

        $components = $this->components;

        if ($components->count() !== 4) {
            return false;
        }

        $componentNames = $components->pluck('name')->map(fn($name) => strtolower($name))->toArray();
        $requiredComponents = ['music', 'arts', 'physical education', 'health'];

        foreach ($requiredComponents as $component) {
            if (!in_array($component, $componentNames) &&
                !in_array(strtolower(substr($component, 0, 5)), $componentNames)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the computed final grade for this subject for a student
     */
    public function computeFinalGradeForStudent($studentId, $term)
    {
        if ($this->is_component) {
            // Regular component-level grade calculation
            return $this->calculateSubjectGrade($studentId, $term);
        }

        if ($this->getIsMAPEHAttribute()) {
            // Weighted average of component grades
            $components = $this->components;
            $totalWeightedGrade = 0;
            $totalWeight = 0;

            foreach ($components as $component) {
                $componentGrade = $component->calculateSubjectGrade($studentId, $term);
                if ($componentGrade !== null) {
                    $totalWeightedGrade += ($componentGrade * $component->component_weight);
                    $totalWeight += $component->component_weight;
                }
            }

            return $totalWeight > 0 ? ($totalWeightedGrade / $totalWeight) : null;
        }

        // Regular subject grade calculation
        return $this->calculateSubjectGrade($studentId, $term);
    }

    /**
     * Calculate regular grade for a subject
     */
    private function calculateSubjectGrade($studentId, $term)
    {
        // Get all grades for this student, subject, and term
        $grades = $this->grades()
            ->where('student_id', $studentId)
            ->where('term', $term)
            ->get();

        // Return null if no grades found
        if ($grades->isEmpty()) {
            return null;
        }

        // Get grade configuration for this subject
        $config = $this->gradeConfiguration;

        if (!$config) {
            // Use default configuration if not set
            $ww = 0.30; // Written work - 30%
            $pt = 0.50; // Performance task - 50%
            $qa = 0.20; // Quarterly assessment - 20%
        } else {
            $ww = $config->written_work_percentage / 100;
            $pt = $config->performance_task_percentage / 100;
            $qa = $config->quarterly_assessment_percentage / 100;
        }

        // Group grades by type
        $writtenWorks = $grades->where('grade_type', 'written_work');
        $performanceTasks = $grades->where('grade_type', 'performance_task');
        $quarterlyAssessments = $grades->where(function($query) {
            return $query->where('grade_type', 'quarterly_assessment')
                         ->orWhere('grade_type', 'quarterly')
                         ->orWhere('grade_type', 'quarterly_exam');
        });

        // Calculate average for each component
        $wwAvg = $this->calculateComponentAverage($writtenWorks);
        $ptAvg = $this->calculateComponentAverage($performanceTasks);
        $qaAvg = $this->calculateComponentAverage($quarterlyAssessments);

        // Calculate weighted final grade
        $finalGrade = 0;
        $weightSum = 0;

        if ($wwAvg !== null) {
            $finalGrade += $wwAvg * $ww;
            $weightSum += $ww;
        }

        if ($ptAvg !== null) {
            $finalGrade += $ptAvg * $pt;
            $weightSum += $pt;
        }

        if ($qaAvg !== null) {
            $finalGrade += $qaAvg * $qa;
            $weightSum += $qa;
        }

        // Return final grade (normalized if not all components are present)
        return $weightSum > 0 ? ($finalGrade / $weightSum) : null;
    }

    /**
     * Calculate the average for a specific grade component
     * Uses total score divided by total max score method
     */
    private function calculateComponentAverage($grades)
    {
        if ($grades->isEmpty()) {
            return null;
        }

        $totalScore = 0;
        $totalMaxScore = 0;

        foreach ($grades as $grade) {
            if ($grade->max_score > 0) {
                $totalScore += $grade->score;
                $totalMaxScore += $grade->max_score;
            }
        }

        return $totalMaxScore > 0 ? ($totalScore / $totalMaxScore) * 100 : 0;
    }

    /**
     * Scope a query to only include active subjects.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include subjects of a specific grade level.
     */
    public function scopeOfGradeLevel($query, $gradeLevel)
    {
        return $query->where('grade_level', $gradeLevel);
    }

    /**
     * Scope a query to only include subjects from a specific school.
     */
    public function scopeFromSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope a query to only include parent subjects (not components).
     */
    public function scopeParentOnly($query)
    {
        return $query->where('is_component', false);
    }

    /**
     * Scope a query to only include MAPEH subjects.
     */
    public function scopeMAPEH($query)
    {
        return $query->whereHas('components', function($q) {
            $q->whereIn('name', ['Music', 'Arts', 'Physical Education', 'Health']);
        })->where('is_component', false);
    }
}
