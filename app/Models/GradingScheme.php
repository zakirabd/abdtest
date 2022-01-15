<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingScheme extends Model
{
    use HasFactory;

    protected $table = "grading_schemes";

    protected $fillable = [
        'id',
        'countries_id',
        'education_degree_id',
        'min_value',
        'max_value'
    ];

    public function countries (){
        return $this->belongsTo("App\Models\Countries");
    }

    public function education_degree(){
        return $this->belongsTo("App\Models\EducationDegree");
    }
}
