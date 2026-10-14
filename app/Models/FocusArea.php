<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FocusArea extends Model
{
    protected $fillable = ['number', 'title', 'description', 'icon', 'sort_order', 'is_visible'];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }
}
