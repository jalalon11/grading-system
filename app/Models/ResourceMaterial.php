<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceMaterial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'url',
        'category_id',
        'quarter',
        'is_active',
        'click_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'click_count' => 'integer',
    ];

    /**
     * Get the category that owns the resource.
     */
    public function category()
    {
        return $this->belongsTo(ResourceCategory::class);
    }

    /**
     * Add category_color attribute
     *
     * @return string
     */
    public function getCategoryColorAttribute()
    {
        return $this->category ? $this->category->color : 'primary';
    }

    /**
     * Add icon attribute
     *
     * @return string
     */
    public function getIconAttribute()
    {
        return $this->category ? $this->category->icon : 'file-alt';
    }

    /**
     * Add category_name attribute
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : 'Uncategorized';
    }

    /**
     * Get the quarter name
     *
     * @return string
     */
    public function getQuarterNameAttribute()
    {
        $quarters = [
            1 => '1st Quarter',
            2 => '2nd Quarter',
            3 => '3rd Quarter',
            4 => '4th Quarter',
        ];

        return $this->quarter ? ($quarters[$this->quarter] ?? 'Unknown Quarter') : 'Unassigned';
    }
}