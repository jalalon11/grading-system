<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'grade_level',
        'adviser_id',
        'school_id',
        'school_year',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the school this section belongs to
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the students in this section
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the adviser (teacher) of this section
     */
    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    /**
     * Get the subjects taught in this section
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'section_subject')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }

    /**
     * Get the teachers assigned to this section through subjects
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'section_subject', 'section_id', 'teacher_id')
            ->withPivot('subject_id')
            ->withTimestamps();
    }
}
