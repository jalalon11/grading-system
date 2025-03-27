<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeConfiguration extends Model
{
    protected $fillable = [
        'subject_id',
        'written_work_percentage',
        'performance_task_percentage',
        'quarterly_assessment_percentage',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Validate that percentages sum to 100
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $total = $model->written_work_percentage + $model->performance_task_percentage + $model->quarterly_assessment_percentage;
            
            // Round to handle floating point precision issues
            if (round($total, 2) != 100.00) {
                throw new \Exception('Percentages must sum to 100%');
            }
        });
    }
}
