<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryWiseEducation extends Model
{
    use HasFactory;

    protected $table = 'country_wise_education';

    protected $fillable = [
        'residental_country_id',
        'residental_degree_id',
        'destination_country_id',
        'destination_degree_id'
    ];
}
