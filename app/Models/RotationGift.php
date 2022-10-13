<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationGift extends Model
{
    use HasFactory;
    protected $table = 'rotation_gifts';
    protected $fillable = [
        'ratio',
        'coins',
        'rotation_id'
    ];
}
