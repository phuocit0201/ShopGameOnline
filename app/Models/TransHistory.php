<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransHistory extends Model
{
    use HasFactory;
    protected $table = "transaction_history";
    protected $fillable = [
        'user_id',
        'action_id',
        'action_flag',
        'after_money',
        'transaction_money',
        'befor_money',
        'note'
    ];

}
