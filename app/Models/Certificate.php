<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year',
        'semester',
        'average_grade',
        'honor_type', // 'with_honors' or 'high_honors'
        'issued_date',
        'issued_by',
    ];

    protected $casts = [
        'average_grade' => 'decimal:2',
        'issued_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public static function determineHonorType(float $averageGrade): ?string
    {
        if ($averageGrade >= 90) {
            return 'high_honors';
        } elseif ($averageGrade >= 87) {
            return 'with_honors';
        }
        return null;
    }
} 