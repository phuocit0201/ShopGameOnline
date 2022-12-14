<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RotationLuck extends Model
{
    use HasFactory;
    protected $table = 'rotation_luck';
    protected $fillable = [
        'rotation_name',
        'img',
        'img_gift',
        'price',
        'slug',
        'status'
    ];
}
