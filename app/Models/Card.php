<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $table = 'cards';
    protected $fillable = [
        'user_id',
        'face_value_id',
        'request_id',
        'seri',
        'code',
        'value',
        'status',
        'amount',
        ''
    ];
}
