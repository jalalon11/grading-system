<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'address',
        'school_id',
        'is_teacher_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_teacher_admin' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Get the school this user belongs to
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get sections where this user is the homeroom teacher
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Get subjects taught by this teacher
     */
    public function subjects()
    {
        // Use the section_subject pivot table to get subjects
        return $this->belongsToMany(Subject::class, 'section_subject', 'teacher_id', 'subject_id')
            ->withPivot('section_id')
            ->withTimestamps();
    }

    /**
     * Direct access to all subject IDs for this teacher via section_subject
     *
     * @return array
     */
    public function getSubjectIds()
    {
        return DB::table('section_subject')
            ->where('teacher_id', $this->id)
            ->pluck('subject_id')
            ->unique()
            ->toArray();
    }

    /**
     * Get attendances recorded by this teacher
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Check if user is teacher admin
     */
    public function isTeacherAdmin(): bool
    {
        return $this->role === 'teacher' && $this->is_teacher_admin;
    }

    /**
     * Get all teacher admins for a school
     */
    public static function getTeacherAdmins($schoolId)
    {
        return static::where('school_id', $schoolId)
            ->where('role', 'teacher')
            ->where('is_teacher_admin', true)
            ->get();
    }

    /**
     * Check if school can have more teacher admins
     */
    public static function canAddTeacherAdmin($schoolId): bool
    {
        return static::where('school_id', $schoolId)
            ->where('is_teacher_admin', true)
            ->count() < 2;
    }
}
