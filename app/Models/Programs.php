<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programs extends Model
{
    use HasFactory;

    protected $table = 'programs';

    protected $fillable = [
        'country_id',
        'state_id',
        'city_id',
        'institution_id',
        'fee_amount',
        'fee_currency_id',
        'education_degree_id',
        'study_duration',
        'start_date',
        'deadline',
        'gpa',
        'schoolarship_option',
        'schoolarship_type',
        'user_id',
        'video_link',
        'active',
        'education_language_id',
        'application_fee',
        'fee_type',
        'local_exam',
        'required_education_level'
    ];

    // protected $appends = ['specialty_exams'];

    public function program_discipline(){
        return $this->belongsToMany("App\Models\Programs", "program_discipline", "programs_id", "discipline_id");
    }

    // public function getDisciplineAttribute(){
    //     return ProgramDiscipline::where('programs_id', $this->id)->get();
    // }

    public function discipline(){
        return $this->hasMany("App\Models\ProgramDiscipline");
    }

    // public function getSpecialtyExamsAttribute(){
    //     return ProgramExams::where('program_id', $this->id)->with('exam')->get();
    // }

    public function education_degree(){
        return $this->belongsTo('App\Models\EducationDegree');
    }



}
