<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // If section_id is not set, try to find it from student_section table
            if (empty($model->section_id) && !empty($model->student_id)) {
                $sectionId = DB::table('student_section')
                    ->where('student_id', $model->student_id)
                    ->value('section_id');
                
                if ($sectionId) {
                    $model->section_id = $sectionId;
                    Log::info('Setting section_id from boot method', [
                        'student_id' => $model->student_id,
                        'section_id' => $sectionId
                    ]);
                } else {
                    Log::warning('Unable to find section_id for student', [
                        'student_id' => $model->student_id
                    ]);
                }
            }
        });
    }

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
