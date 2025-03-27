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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
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
}
