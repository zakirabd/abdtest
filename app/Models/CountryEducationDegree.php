<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryEducationDegree extends Model
{
    use HasFactory;
    protected $table = 'country_education_degree';

    protected $fillable = [
        'countries_id',
        'education_degree_id',
    ];
}
