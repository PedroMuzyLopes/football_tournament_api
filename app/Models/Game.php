<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $casts = [
        'start_time' => 'date:h:m',
        'end_time' => 'date:h:m',
    ];

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'team1',
        'team2',
        'team1_score',
        'team2_score'
    ];
}
