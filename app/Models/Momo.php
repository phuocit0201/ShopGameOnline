<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Momo extends Model
{
    use HasFactory;
    protected $table = 'momo';
    protected $fillable = [
        'phone_number',
        'full_name',
        'token_api',
        'note',
        'status'
    ];
}
