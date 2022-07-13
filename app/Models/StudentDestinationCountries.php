<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDestinationCountries extends Model
{
    use HasFactory;

    protected $table = 'student_destination_countries';

    protected $fillable = [
        'users_id',
        'country_id',
        'name',
    ];
    
}
