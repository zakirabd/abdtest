<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationDegreeTranslate extends Model
{
    use HasFactory;
    protected $table = 'education_degree_translate';

    protected $fillable = [
        'education_degree_id',
        'education_type',
        'user_id',
        'lang_id',
        'active',
    ];

    public function education_degree(){
        return $this->belongsTo('App\Models\EducationDegree');
    }
}
