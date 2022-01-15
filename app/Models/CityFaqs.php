<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityFaqs extends Model
{
    use HasFactory;
    protected $table = 'city_faqs';

    protected $fillable = [
        'id',
        'city_id',
        'active',
    ];
}
