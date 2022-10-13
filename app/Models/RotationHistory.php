<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationHistory extends Model
{
    use HasFactory;
    protected $table = 'rotation_history';
    protected $fillable = [
        'coins',
        'rotation_id',
        'user_id',
        'status'
    ];
}
