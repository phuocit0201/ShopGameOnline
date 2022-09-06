<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheSieuRe extends Model
{
    use HasFactory;
    protected $table = 'thesieure';
    protected $fillable = [
        'username',
        'full_name',
        'access_token' ,
        'partner_key',
        'partner_id',
        'note',
        'status_bank',   
        'status_card'   
    ];
}
