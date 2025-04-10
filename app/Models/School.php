<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
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
        'address',
        'principal',
        'logo_path',
        'grade_levels',
        'school_division_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'grade_levels' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the full URL for the school logo
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo_path) {
            return null;
        }

        $disk = config('filesystems.default', 'public');
        
        if ($disk === 's3') {
            // For S3/Laravel Cloud storage
            return config('filesystems.disks.s3.url') . '/' . $this->logo_path;
        }
        
        // For local storage, just return the path relative to public directory
        return '/' . $this->logo_path;
    }

    /**
     * Get the school division that this school belongs to
     */
    public function schoolDivision(): BelongsTo
    {
        return $this->belongsTo(SchoolDivision::class);
    }

    /**
     * Get the sections in this school
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Get the teachers in this school
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }

    /**
     * Get all users in this school
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
