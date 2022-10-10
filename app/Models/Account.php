<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'account_game';
    protected $fillable = [
        'class',
        'level',
        'server_game',
        'family',
        'import_price',
        'sale_price',
        'description',
        'avatar',
        'status',
        'category_id',
        'username',
        'password'
    ];
}
