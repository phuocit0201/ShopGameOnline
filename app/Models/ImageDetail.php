<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageDetail extends Model
{
    use HasFactory;
    protected $table = 'images_details';
    protected $fillable = [
        'link_img',
        'account_id'
    ];
}
