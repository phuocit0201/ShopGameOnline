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
        'token_api' ,
        'partner_key',
        'partner_id',
        'note',
        'status_bank',   
        'status_card'   
    ];
}
