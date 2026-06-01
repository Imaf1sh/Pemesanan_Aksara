<?php

namespace App\Modules\POS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'initial_cash',
        'open_time',
        'close_time',
    ];

    protected $casts = [
        'open_time' => 'datetime',
        'close_time' => 'datetime',
    ];
}
