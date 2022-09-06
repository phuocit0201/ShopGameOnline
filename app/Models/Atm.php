<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atm extends Model
{
    use HasFactory;
    protected $table = 'atm';
    protected $fillable = [
        'account_number',
        'full_name',
        'password',
        'token_api',
        'note',
        'status',
        'bank_id'
    ];
}
