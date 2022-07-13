<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryEducationDegreeTranslate extends Model
{
    use HasFactory;
    protected $table = 'country_education_degree_translate';

    protected $fillable = [
        'country_education_degree_id',
        'name',
        'lang_id'
    ];

    public function country_education_degree(){
        return $this->belongsTo('App\Models\CountryEducationDegree');
    }
}
