<?php

namespace App\Services;
use App\Models\StudentDestinationCountries;
use App\Models\StudentDestinationDisciplines;
use App\Models\StudentEducationBackground;
use App\Models\StudentExams;
use App\Models\StudentPersonalInfo;
use App\Models\CountryWiseEducation;
use App\Models\ProgramsTranslate;
use App\Models\ProgramExams;
use App\Models\ProgramExamsSubSections;
use App\Models\Countries;
use App\Models\CountriesTranslate;
use App\Models\Institutions;
use App\Models\InstitutionsTranslate;
use App\Models\Programs;
use App\Models\EducationDegreeTranslate;
use App\Models\EducationLanguageTranslate;
use App\Models\Currencies;
use App\Models\Exams;
use App\Models\StudentAppliedProgram;
use App\Models\StudentsPrograms;
use App\Models\User;
/**
 * Class StudentRoleMainService
 * @package App\Services
 */
class StudentRoleMainService
{
    private $education_background;
    private $exams;
    // private $destination_country;
    // private $destination_disciplines;
    private $request;
    private $personal_info;
    private $user;
    private $stuent_programs;

    public function __construct($request)
    {
        $this->request = $request;
        if(auth()->user()->role === 'student'){
            $this->education_background = StudentEducationBackground::where('users_id', auth()->user()->id)->first();
            $this->exams = StudentExams::where('users_id', auth()->user()->id)->get();
        }else if(auth()->user()->role === 'uni_rep'){
            $this->education_background = StudentEducationBackground::where('users_id', $this->request->student_id)->first();
            $this->exams = StudentExams::where('users_id', $this->request->student_id)->get();
        }


        // $this->destination_country = StudentDestinationCountries::where('users_id', auth()->user()->id)->pluck('country_id');
        // $this->destination_disciplines = StudentDestinationDisciplines::where('users_id', auth()->user()->id)->pluck('discipline_id');
        $this->personal_info = StudentPersonalInfo::where('users_id', auth()->user()->id)->first();
        $this->user = User::findOrFail(auth()->user()->id);
        $this->stuent_programs = StudentsPrograms::where('user_id', auth()->user()->id)->pluck('programs_id');
        $this->program = ProgramsTranslate::whereIn('program_id', $this->stuent_programs)->with('program')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1);
    }

    public function getEligiblePrograms(){

        // if($this->request->keyword != ''){
        //     $this->program->where('name', 'like', "%{$this->request->keyword}%");
        // }

        // if($this->request->filterByCountry != ''){
        //     $this->program->whereHas('program', function($q){
        //         $q->where('country_id', $this->request->filterByCountry);
        //     });
        // }

        // if($this->request->filterByState != ''){
        //     $this->program->whereHas('program', function($q){
        //         $q->where('state_id', $this->request->filterByState);
        //     });
        // }

        // if($this->request->filterByCity != ''){
        //     $this->program->whereHas('program', function($q){
        //         $q->where('city_id', $this->request->filterByCity);
        //     });
        // }

        // if($this->request->filterByStatus != ''){
        //     $this->program->whereHas('program', function($q){
        //         $q->where('active', $this->request->filterByStatus);
        //     });
        // }

        // if($this->request->filterByInstitution != ''){
        //     $this->program->whereHas('program', function($q){
        //         $q->where('institution_id', $this->request->filterByInstitution);
        //     });
        // }

        // if($this->request->filterByLevel != ''){
        //     $this->program->whereHas('program', function($q){
        //         $q->where('education_degree_id', $this->request->filterByLevel);
        //     });
        // }

        // if($this->request->filterByDiscipline != ''){
        //     $this->program->whereHas('program', function($q){
        //     //    $q->where('discipline.discipline_id', $this->request->filterByDiscipline);
        //         $q->whereHas('discipline', function ($discipline){
        //             $discipline->where('discipline_id', $this->request->filterByDiscipline);
        //         });

        //     });
        // }
        // if($this->request->filterByDuration != ''){
        //     $this->program->whereHas('program', function($q){
        //         $q->where('study_duration', $this->request->filterByDuration);
        //     });
        // }
        // if($this->request->filterByFee != ''){
        //     $this->program->whereHas('program', function($q){
        //         $q->where('fee_amount','<=' ,6000);
        //     });
        // }

        if(isset($this->request->page) && $this->request->page != ''){
            $programs = $this->program->take($this->request->page* 20)->orderBy('id', 'DESC')->get();
        }else{
            $programs = $this->program->orderBy('id', 'DESC')->get();
        }




        foreach($programs as $item){
            $item->education_degree = EducationDegreeTranslate::where('education_degree_id', $item->program->education_degree_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->education_type;
            $item->education_language = EducationLanguageTranslate::where('education_language_id', $item->program->education_language_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->language;
            $item->currency = Currencies::where('id', $item->program->fee_currency_id)->first()->currency;
            $item->institution = InstitutionsTranslate::where('institutions_id', $item->program->institution_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->name;
            $item->program->specialty_exams = ProgramExams::where('program_id', $item->program_id)->with('exam')->get();

            foreach($item->program->specialty_exams as $exam){
                $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $item->program_id)->get();
            }

            $applied = StudentAppliedProgram::where('student_id', auth()->user()->id)->where('program_id', $item->program->id)->first();
            if($applied){
                $item->applied = '1';
            }else{
                $item->applied = '0';
            }
        }


        return $programs;
    }

    public function getStudentProfileComplete(){
        $education_background = 0;

        if($this->education_background->study_country_id != '' && $this->education_background->study_country_id != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->study_degree_id != '' && $this->education_background->study_degree_id != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->grading_scheme_id != '' && $this->education_background->grading_scheme_id != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->study_gpa != '' && $this->education_background->study_gpa != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->institution_name != '' && $this->education_background->institution_name != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->study_language != '' && $this->education_background->study_language != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->attended_institution_from != '' && $this->education_background->attended_institution_from != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->attended_institution_to != '' && $this->education_background->attended_institution_to != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->degree_award != '' && $this->education_background->degree_award != null){
            $education_background = $education_background + 10;
        }

        if($this->education_background->address != '' && $this->education_background->address != null){
            $education_background = $education_background + 10;
        }
// /////////////////////////////////////////////////////////////////////////
        $personal_info = 0;

        if(isset($this->personal_info->country) && $this->personal_info->country != '' && $this->personal_info->country != null){
            $personal_info = $personal_info + 100/8;
        }


        if(isset($this->personal_info->city) && $this->personal_info->city != '' && $this->personal_info->city != null){
            $personal_info = $personal_info + 100/8;
        }

        if(isset($this->personal_info->postal_code) && $this->personal_info->postal_code != '' && $this->personal_info->postal_code != null){
            $personal_info = $personal_info + 100/8;
        }

        if(isset($this->personal_info->address) && $this->personal_info->address != '' && $this->personal_info->address != null){
            $personal_info = $personal_info + 100/8;
        }

        if(isset($this->user->first_name) && $this->user->first_name != '' && $this->user->first_name != null){
            $personal_info = $personal_info + 100/8;
        }
        if(isset($this->user->last_name) && $this->user->last_name != '' && $this->user->last_name != null){
            $personal_info = $personal_info + 100/8;
        }
        if(isset($this->user->email) && $this->user->email != '' && $this->user->email != null){
            $personal_info = $personal_info + 100/8;
        }
        if(isset($this->user->phone_number) && $this->user->phone_number != '' && $this->user->phone_number != null){
            $personal_info = $personal_info + 100/8;
        }


        $student_exam = 0;

        foreach($this->exams as $item){
            if($item->date == null || $item->date == ''){
                $student_exam = $student_exam + 100/(count($this->exams));

            }
        }
        $student_exam = 100 -  $student_exam;
        $final_data = ['education_background' => $education_background, 'personal_info' => $personal_info, 'student_exam' => $student_exam];
        return $final_data;
    }

    public function getStudenEducationBackground(){
        $education_background =$this->education_background;
        $education_background->education_degree = EducationDegreeTranslate::where('education_degree_id', $education_background->study_degree_id)
                                ->where('lang_id', $this->request->lang_id?$this->request->lang_id:1)
                                ->first()->education_type;
        return $education_background;
    }
    public function getStudenExamScore(){
        foreach($this->exams as $item){
            $item->name = Exams::findOrFail($item->exam_id);
        }
        return  $this->exams;
    }

    public function getStudenStudyDestination(){
        $destination_countries = StudentDestinationCountries::where('users_id', auth()->user()->id)->get();
        $destination_disciplines = StudentDestinationDisciplines::where('users_id', auth()->user()->id)->get();

        return ['destination_countres' => $destination_countries, 'destination_disciplines' => $destination_disciplines];
    }
}
