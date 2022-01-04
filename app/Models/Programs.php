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

    ];

    protected $appends = ['specialty_exams', 'discipline'];

    public function program_discipline(){
        return $this->belongsToMany("App\Models\Programs", "program_discipline", "program_id", "discipline_id");
    }


    public function getDisciplineAttribute(){
        return ProgramDiscipline::where('program_id', $this->id)->get();
    }

    public function getSpecialtyExamsAttribute(){
        return ProgramExams::where('program_id', $this->id)->with('exam')->get();
    }


}
