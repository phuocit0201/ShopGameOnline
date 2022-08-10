<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telco extends Model
{
    use HasFactory;
    protected $table = 'telco';
    protected $fillable = [
        'telco_name',
        'status'
    ];
}
