<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryFags extends Model
{
    use HasFactory;

    protected $table = 'country_fags';

    protected $fillable = [
        'id',
        'countries_id',
        'type',
        'active',
    ];
}
