<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['task', 'description', 'is_done', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];
}
