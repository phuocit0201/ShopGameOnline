<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'account_game';
    protected $fillable = [
        'info1',
        'info2',
        'info3',
        'import_price',
        'sale_price',
        'description',
        'status',
        'category_id'
    ];
}
