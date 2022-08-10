<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceValue extends Model
{
    use HasFactory;
    protected $table = 'face_value';
    protected $fillable = [
        'telco_id',
        'price',
        'fees',
        'penalty',
        'status'
    ];
}
