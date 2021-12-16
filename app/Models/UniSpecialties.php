<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Specialties;
use App\Models\Disciplines;
use App\Models\Currencies;
use App\Models\EducationDegree;
use App\Models\GradingScheme;
use App\Models\SpecialtyLanguage;
use App\Models\SpecialtyExams;

class UniSpecialties extends Model
{
    use HasFactory;
    protected $table = 'uni_specialties';

    protected $fillable = [
        'description',
        'specialty_id',
        'discipline_id',
        'fee_amount',
        'fee_currency_id',
        'education_degree_id',
        'study_duration',
        'grading_scheme_id',
        'program_format',
        'start_date',
        'deadline',
        'gpa',
        'schoolarship_option',
        'user_id',
        'lang_id',
        'active'
    ];

    protected $hidden = [
        'specialty_id',
        'discipline_id',
        'fee_currency_id',
        'education_degree_id',
        'grading_scheme_id',
        'user_id',
        'lang_id',
        'university_id'
    ];
    protected $appends = ['specialty', 'discipline', 'fee_currency', 'education_degree', 'grading_scheme', 'specialty_language', 'specialty_exams', 'university'];

    public function getSpecialtyAttribute(){
        $specialty = Specialties::where('id', $this->specialty_id)->first();
        return $specialty;
    }

    public function getDisciplineAttribute(){
        $discipline = Disciplines::where('id', $this->discipline_id)->first();
        return $discipline;
    }

    public function getFeeCurrencyAttribute(){
        $fee_currency = Currencies::where('id', $this->fee_currency_id)->first();
        return $fee_currency->currency;
    }

    public function getEducationDegreeAttribute(){
        $education_degree = EducationDegree::where('id', $this->education_degree_id)->first();
        return $education_degree->education_type;
    }

    public function getGradingSchemeAttribute(){
        $grading_scheme = GradingScheme::where('id', $this->grading_scheme_id)->first();
        return $grading_scheme->type;
    }

    public function getSpecialtyLanguageAttribute(){
        $language = SpecialtyLanguage::where('specialty_id', $this->id)->with('language')->get();

        return $language;
    }

    public function getSpecialtyExamsAttribute(){
        $exams = SpecialtyExams::where('specialty_id', $this->id)->with(['exam'])->get();

        return $exams;
    }

    public function getUniversityAttribute(){
        $exams = Education::where('id', $this->university_id)->first();
        return $exams;
    }

}
