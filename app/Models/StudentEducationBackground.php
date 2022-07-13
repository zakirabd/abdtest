<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEducationBackground extends Model
{
    use HasFactory;

    protected $table = 'student_education_backgrounds';

    protected $fillable = [
        'users_id',
        'study_country_id',
        'study_degree_id',
        'study_sub_degree_id',
        'grading_scheme_id',
        'study_gpa',
        'institution_name',
        'study_language',
        'attended_institution_from',
        'attended_institution_to',
        'degree_award',
        'address'
    ];
}
