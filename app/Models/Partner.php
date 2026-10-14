<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name', 'tagline', 'description', 'logo_path', 'sort_order', 'is_visible',
    ];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }
}
