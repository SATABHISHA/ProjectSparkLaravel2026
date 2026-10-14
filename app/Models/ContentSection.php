<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentSection extends Model
{
    protected $fillable = [
        'slug', 'eyebrow', 'title', 'subtitle', 'body', 'items', 'sort_order', 'is_visible',
    ];

    protected function casts(): array
    {
        return ['items' => 'array', 'is_visible' => 'boolean'];
    }
}
