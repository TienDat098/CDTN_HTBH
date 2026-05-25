<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livestream extends Model
{
    protected $fillable = [
        'title',
        'description',
        'youtube_url',
        'youtube_video_id',
        'is_active',
        'started_at',
        'ended_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'livestream_product');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}