<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Components\Model\HasStoredFiles;

class Contest extends Model
{
    use HasStoredFiles;

    const STATUS_IS_NOT_ACTIVE = 0;
    const STATUS_IS_ACTIVE = 1;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_active', 'headline', 'subheadline', 'explaining_text', 'banner',
    ];

    /**
     * The attributes that stores file paths in corresponding storages
     *
     * @var array
     */
    protected static $stored_files = [
        'public' => [
            'banner' => 'images/contest-banners/{Y}',
        ],
    ];

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}

