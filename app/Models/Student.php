<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'student_id',
        'lrn',
        'address',
        'guardian_name',
        'guardian_contact',
        'section_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the student's full name
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . 
               ($this->middle_name ? $this->middle_name . ' ' : '') . 
               $this->last_name;
    }

    /**
     * Get the section this student belongs to
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the grades for this student
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the attendance records for this student
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
