<?php

namespace App\Services;

use App\Models\Currencies;
use App\Models\EducationDegreeTranslate;
use App\Models\EducationLanguageTranslate;
use App\Models\InstitutionsTranslate;
use App\Models\ProgramExams;
use App\Models\ProgramExamsSubSections;
use App\Models\ProgramsTranslate;
use App\Models\StudentsPrograms;


/**
 * Class ProgramService
 * @package App\Services
 */
class ProgramService
{
    private $program;
    private $request;


    public function __construct($request)
    {
        $this->request = $request;
        if(auth()->user() && auth()->user()->role == 'uni_rep'){
           $this->program = ProgramsTranslate::with('program')->where('user_id', auth()->user()->id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)
                                                              ->where('user_id','1')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1);
        }else if(auth()->user() && auth()->user()->role == 'super_admin'){
            $this->program = ProgramsTranslate::with('program')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1);
        }else {
            $this->program = ProgramsTranslate::with('program')->where('active', '1')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1);
        }


    }

    public function getPrograms(){
        if($this->request->keyword != ''){
            $this->program->where('name', 'like', "%{$this->request->keyword}%");
        }

        if($this->request->filterByCountry != ''){
            $this->program->whereHas('program', function($q){
                $q->where('country_id', $this->request->filterByCountry);
            });
        }

        if($this->request->filterByState != ''){
            $this->program->whereHas('program', function($q){
                $q->where('state_id', $this->request->filterByState);
            });
        }

        if($this->request->filterByCity != ''){
            $this->program->whereHas('program', function($q){
                $q->where('city_id', $this->request->filterByCity);
            });
        }

        if($this->request->filterByStatus != ''){
            $this->program->whereHas('program', function($q){
                $q->where('active', $this->request->filterByStatus);
            });
        }

        if($this->request->filterByInstitution != ''){
            $this->program->whereHas('program', function($q){
                $q->where('institution_id', $this->request->filterByInstitution);
            });
        }

        if($this->request->filterByLevel != ''){
            $this->program->whereHas('program', function($q){
                $q->where('education_degree_id', $this->request->filterByLevel);
            });
        }

        if($this->request->filterByDiscipline != ''){
            $this->program->whereHas('program', function($q){
            //    $q->where('discipline.discipline_id', $this->request->filterByDiscipline);
                $q->whereHas('discipline', function ($discipline){
                    $discipline->where('discipline_id', $this->request->filterByDiscipline);
                });

            });
        }
        if($this->request->filterByDuration != ''){
            $this->program->whereHas('program', function($q){
                $q->where('study_duration', $this->request->filterByDuration);
            });
        }
        if($this->request->filterByFee != ''){
            $this->program->whereHas('program', function($q){
                $q->where('fee_amount','<=' ,6000);
            });
        }


        $programs = $this->program->take($this->request->page * 20)->orderBy('id', 'DESC')->get();

        $final_data = [];

        foreach($programs as $item){
            if(auth()->user() != null && auth()->user()->role == 'student'){
                $student_programs = StudentsPrograms::where('user_id', auth()->user()->id)->where('programs_id', $item->program_id)->first();
                if(isset($student_programs)){
                    $item->eligible = '1';
                }else{
                    $item->eligible = '0';
                }
            }

            $item->education_degree = EducationDegreeTranslate::where('education_degree_id', $item->program->education_degree_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->education_type;
            $item->education_language = EducationLanguageTranslate::where('education_language_id', $item->program->education_language_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->language;
            $item->currency = Currencies::where('id', $item->program->fee_currency_id)->first()->currency;
            $item->institution = InstitutionsTranslate::where('institutions_id', $item->program->institution_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->name;
            $item->program->specialty_exams = ProgramExams::where('program_id', $item->program_id)->with('exam')->get();

            foreach($item->program->specialty_exams as $exam){
                $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $item->program_id)->get();
            }

            array_push($final_data, $item);
        }

        return $final_data;
    }
}
