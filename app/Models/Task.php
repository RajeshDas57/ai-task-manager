<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'is_completed'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'date',
    ];
}