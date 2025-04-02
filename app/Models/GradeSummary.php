<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSummary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'section_id',
        'subject_id',
        'quarter',
        'written_work_ps',
        'written_work_ws',
        'performance_task_ps',
        'performance_task_ws',
        'quarterly_assessment_ps',
        'quarterly_assessment_ws',
        'initial_grade',
        'quarterly_grade',
        'remarks'
    ];

    /**
     * Get the student associated with the grade summary.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the section associated with the grade summary.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the subject associated with the grade summary.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
