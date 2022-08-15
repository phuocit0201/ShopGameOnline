<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $table = "transfers";
    protected $fillable = [
        'user_id',
        'type_transfer',
        'tranding_code',
        'message',
        'amount'
    ];
}
