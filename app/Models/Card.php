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
        'telco',
        'declare_value',
        'fees',
        'penalty',
        'serial',
        'code',
        'value',
        'amount',
        'status',
    ];
}
