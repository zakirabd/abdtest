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
use App\Models\User;
/**
 * Class StudentMatchingService
 * @package App\Services
 */
class StudentMatchingService
{
    private $education_background;
    private $exams;
    private $destination_country;
    private $destination_disciplines;
    private $request;
    private $personal_info;
    private $user;
    public function __construct($request)
    {
        $this->request = $request;

    //    if(auth()->user()->role == 'student'){
        $this->education_background = StudentEducationBackground::where('users_id', $this->request->id)->first();
        $this->exams = StudentExams::where('users_id', $this->request->id)->get();



        $this->destination_country = StudentDestinationCountries::where('users_id', $this->request->id)->pluck('country_id');
        $this->destination_disciplines = StudentDestinationDisciplines::where('users_id', $this->request->id)->pluck('discipline_id');
        $this->personal_info = StudentPersonalInfo::where('users_id', $this->request->id)->first();
        $this->user = User::findOrFail($this->request->id);
    //    }
    }

    public function getEligiblePrograms(){



        $suitable_countries = CountryWiseEducation::where('residental_country_id', $this->education_background->study_country_id)
                            ->whereIn('destination_country_id', $this->destination_country)
                            ->where('residental_degree_id', $this->education_background->study_degree_id)->pluck('destination_country_id');

        $suitable_degree = CountryWiseEducation::where('residental_country_id', $this->education_background->study_country_id)
                            ->whereIn('destination_country_id', $this->destination_country)
                            ->where('residental_degree_id', $this->education_background->study_degree_id)->pluck('destination_degree_id');


        $final_programs = [];

        if(isset($suitable_countries) && count($suitable_countries) != 0){

            $programs_formal = ProgramsTranslate::where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use ($suitable_countries, $suitable_degree) {
                $q->whereIn('country_id', $suitable_countries)
                ->whereIn('education_degree_id', $suitable_degree)
                ->whereHas('discipline', function ($query) {
                    $query->whereIn('discipline_id', $this->destination_disciplines);
                })
                ->where('gpa','<=', $this->education_background->study_gpa);
            })
            ->with('program')

            ->get();


            foreach($programs_formal as $item){
                $item->program->specialty_exams = ProgramExams::where('program_id', $item->program_id)->with('exam')->get();
                $apt_exam = ProgramExams::where('program_id', $item->program_id)
                ->with('exam')
                ->whereHas('exam', function($q){
                    $q->where('type', '0');
                })
                ->get();

                if($item->program->local_exam == '0'){
                     $lang_exam = ProgramExams::where('program_id', $item->program_id)
                        ->with('exam')
                        ->whereHas('exam', function($q){
                            $q->where('type', '1');
                        })
                        ->get();
                }else{
                    $lang_exam = [];
                }


                $item->apt_exam = $apt_exam;
                $item->lang_exam = $lang_exam;
                array_push($final_programs, $item);
            }

        }

        if($this->education_background->study_degree_id == '1' || $this->education_background->study_degree_id == '6'){
            $programs = ProgramsTranslate::where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use ( $suitable_countries) {
                $q->whereIn('country_id', $this->destination_country)
                ->whereNotIn('country_id', $suitable_countries)
                ->whereHas('discipline', function ($query) {
                    $query->whereIn('discipline_id', $this->destination_disciplines);
                })
                ->where('gpa','<=', $this->education_background->study_gpa);

                $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '2')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '6');

            })
            ->with('program')
            ->get();
        }else if($this->education_background->study_degree_id == '2' || $this->education_background->study_degree_id == '2'){
            $programs = ProgramsTranslate::where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use (  $suitable_countries) {
                $q->whereIn('country_id', $this->destination_country)
                ->whereNotIn('country_id', $suitable_countries)
                ->whereHas('discipline', function ($query) {
                    $query->whereIn('discipline_id', $this->destination_disciplines);
                })
                ->where('gpa','<=', $this->education_background->study_gpa);

                $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '2')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '3')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '6');

            })
            ->with('program')
            ->get();
        }else if($this->education_background->study_degree_id == '5'){
            $programs = ProgramsTranslate::where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use ($suitable_countries) {
                $q->whereIn('country_id', $this->destination_country)
                ->whereNotIn('country_id', $suitable_countries)
                ->whereHas('discipline', function ($query) {
                    $query->whereIn('discipline_id', $this->destination_disciplines);
                })
                ->where('gpa','<=', $this->education_background->study_gpa);

                $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '5');

            })
            ->with('program')
            ->get();
        }else if($this->education_background->study_degree_id == '4'){
            $programs = ProgramsTranslate::where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use ($suitable_countries) {
                $q->whereIn('country_id', $this->destination_country)
                ->whereNotIn('country_id', $suitable_countries)
                ->whereHas('discipline', function ($query) {
                    $query->whereIn('discipline_id', $this->destination_disciplines);
                })
                ->where('gpa','<=', $this->education_background->study_gpa);

                $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    ->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '4')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '5');

            })
            ->with('program')
            ->get();
        }


        foreach($programs as $item){
            $item->program->specialty_exams = ProgramExams::where('program_id', $item->program_id)->with('exam')->get();
            $apt_exam = ProgramExams::where('program_id', $item->program_id)
                ->with('exam')
                ->whereHas('exam', function($q){
                    $q->where('type', '0');
                })
                ->get();
            if($item->program->local_exam == '0'){
                $lang_exam = ProgramExams::where('program_id', $item->program_id)
                    ->with('exam')
                    ->whereHas('exam', function($q){
                        $q->where('type', '1');
                    })
                    ->get();
            }else{
                $lang_exam = [];
            }


            $item->apt_exam = $apt_exam;
            $item->lang_exam = $lang_exam;

            array_push($final_programs, $item);
        }

        $filtered_by_exam_program = [];

        foreach($final_programs as $item){
            if(count($item->lang_exam) != 0 && count($item->apt_exam) != 0){
                $lang_exam_arr = [];
                $apt_exam_arr = [];

                foreach($item->apt_exam as $apt ){
                    foreach($this->exams as $exam){
                        if($apt->exam_id == $exam->exam_id && $exam->over_all >= $apt->over_all){
                            array_push($apt_exam_arr, $item);
                        }
                    }
                }

                foreach($item->lang_exam as $lang){
                    foreach($this->exams as $exam){
                        if($lang->exam_id == $exam->exam_id && $exam->over_all >= $lang->over_all){
                            array_push($lang_exam_arr, $item);
                        }
                    }
                }
                if(count($lang_exam_arr) != 0 && count( $apt_exam_arr) != 0){
                    array_push($filtered_by_exam_program, $lang_exam_arr[0]);
                }
            }else if(count($item->lang_exam) == 0 && count($item->apt_exam) != 0){

                $apt_exam_arr = [];

                foreach($item->apt_exam as $apt ){
                    foreach($this->exams as $exam){
                        if($apt->exam_id == $exam->exam_id && $exam->over_all >= $apt->over_all){
                            array_push($apt_exam_arr, $item);
                        }
                    }
                }


                if(count( $apt_exam_arr) != 0){
                    array_push($filtered_by_exam_program, $apt_exam_arr[0]);
                }
            }else if(count($item->lang_exam) != 0 && count($item->apt_exam) == 0){
                $lang_exam_arr = [];

                foreach($item->lang_exam as $lang){
                    foreach($this->exams as $exam){
                        if($lang->exam_id == $exam->exam_id && $exam->over_all >= $lang->over_all){
                            array_push($lang_exam_arr, $item);
                        }
                    }
                }
                if(count($lang_exam_arr) != 0){
                    array_push($filtered_by_exam_program, $lang_exam_arr[0]);
                }
            }else if(count($item->lang_exam) == 0 && count($item->apt_exam) == 0){
                array_push($filtered_by_exam_program, $item);
            }
        }

        $final_data = [];

        foreach($filtered_by_exam_program as $item){
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
        // return response()->json(['edu_back'=> $this->education_background, 'exam' => $this->exams, 'des_count' => $this->destination_country, 'des_discipline' => $this->destination_disciplines]);
    }


    public function matchProgramWithStudents($program){

        $final_data = [];
        $students = User::where('role_id', '3')->pluck('id');
        foreach($students as $student){
            $education_background = StudentEducationBackground::where('users_id', $student)->first();
            $exams = StudentExams::where('users_id', $student)->get();



            $destination_country = StudentDestinationCountries::where('users_id', $student)->pluck('country_id');
            $destination_disciplines = StudentDestinationDisciplines::where('users_id', $student)->pluck('discipline_id');





            $suitable_countries = CountryWiseEducation::where('residental_country_id', $education_background->study_country_id)
                            ->whereIn('destination_country_id', $destination_country)
                            ->where('residental_degree_id', $education_background->study_degree_id)->pluck('destination_country_id');

            $suitable_degree = CountryWiseEducation::where('residental_country_id', $education_background->study_country_id)
                                ->whereIn('destination_country_id', $destination_country)
                                ->where('residental_degree_id', $education_background->study_degree_id)->pluck('destination_degree_id');

            $final_programs = [];
            if(isset($suitable_countries) && count($suitable_countries) != 0){

                $programs_formal = ProgramsTranslate::where('program_id', $program->program_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use ($suitable_countries, $suitable_degree, $destination_disciplines, $education_background) {
                    $q->whereIn('country_id', $suitable_countries)
                    ->whereIn('education_degree_id', $suitable_degree)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa);
                })
                ->with('program')

                ->get();


                foreach($programs_formal as $item){
                    $item->program->specialty_exams = ProgramExams::where('program_id', $item->program_id)->with('exam')->get();
                    $apt_exam = ProgramExams::where('program_id', $item->program_id)
                    ->with('exam')
                    ->whereHas('exam', function($q){
                        $q->where('type', '0');
                    })
                    ->get();

                    if($item->program->local_exam == '0'){
                            $lang_exam = ProgramExams::where('program_id', $item->program_id)
                            ->with('exam')
                            ->whereHas('exam', function($q){
                                $q->where('type', '1');
                            })
                            ->get();
                    }else{
                        $lang_exam = [];
                    }


                    $item->apt_exam = $apt_exam;
                    $item->lang_exam = $lang_exam;
                    array_push($final_programs, $item);
                }

            }

            if($education_background->study_degree_id == '1' || $education_background->study_degree_id == '6'){
                $programs = ProgramsTranslate::where('program_id', $program->program_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use ( $suitable_countries, $education_background, $destination_disciplines, $destination_country) {
                    $q->whereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '1');

                    $q->orWhereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '2');

                    $q->orWhereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '6');
                    // $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    //     ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '2')
                    //     ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '6');

                })
                ->with('program')
                ->get();
            }else if($education_background->study_degree_id == '2' || $education_background->study_degree_id == '2'){
                $programs = ProgramsTranslate::where('program_id', $program->program_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use (  $suitable_countries, $education_background, $destination_disciplines, $destination_country) {
                    $q->whereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '1');

                    $q->orWhereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '2');

                    $q->orWhereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '3');

                    $q->orWhereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '6');

                    // $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    //     ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '2')
                    //     ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '3')
                    //     ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '6');

                })
                ->with('program')
                ->get();
            }else if($education_background->study_degree_id == '5'){
                $programs = ProgramsTranslate::where('program_id', $program->program_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use ($suitable_countries, $education_background, $destination_disciplines, $destination_country) {
                    $q->whereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '1');

                    $q->orWhereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '5');

                    // $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    //     ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '5');

                })
                ->with('program')
                ->get();
            }else if($education_background->study_degree_id == '4'){
                $programs = ProgramsTranslate::where('program_id', $program->program_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id:1 )->whereHas('program', function ($q) use ($suitable_countries, $education_background, $destination_disciplines, $destination_country) {
                    $q->whereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '1');

                    $q->orWhereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '4');

                    $q->orWhereIn('country_id',$destination_country)
                    ->whereNotIn('country_id', $suitable_countries)
                    ->whereHas('discipline', function ($query) use ($destination_disciplines) {
                        $query->whereIn('discipline_id', $destination_disciplines);
                    })
                    ->where('gpa','<=', $education_background->study_gpa)->where('education_degree_id', '5');

                    // $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    //     ->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '4')
                    //     ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '5');

                })
                ->with('program')
                ->get();
            }


            foreach($programs as $item){
                $item->program->specialty_exams = ProgramExams::where('program_id', $item->program_id)->with('exam')->get();
                $apt_exam = ProgramExams::where('program_id', $item->program_id)
                    ->with('exam')
                    ->whereHas('exam', function($q){
                        $q->where('type', '0');
                    })
                    ->get();
                if($item->program->local_exam == '0'){
                    $lang_exam = ProgramExams::where('program_id', $item->program_id)
                        ->with('exam')
                        ->whereHas('exam', function($q){
                            $q->where('type', '1');
                        })
                        ->get();
                }else{
                    $lang_exam = [];
                }


                $item->apt_exam = $apt_exam;
                $item->lang_exam = $lang_exam;

                array_push($final_programs, $item);
            }

            $filtered_by_exam_program = [];

            foreach($final_programs as $item){
                if(count($item->lang_exam) != 0 && count($item->apt_exam) != 0){
                    $lang_exam_arr = [];
                    $apt_exam_arr = [];

                    foreach($item->apt_exam as $apt ){
                        foreach( $exams as $exam){
                            if($apt->exam_id == $exam->exam_id && $exam->over_all >= $apt->over_all){
                                array_push($apt_exam_arr, $item);
                            }
                        }
                    }

                    foreach($item->lang_exam as $lang){
                        foreach( $exams as $exam){
                            if($lang->exam_id == $exam->exam_id && $exam->over_all >= $lang->over_all){
                                array_push($lang_exam_arr, $item);
                            }
                        }
                    }
                    if(count($lang_exam_arr) != 0 && count( $apt_exam_arr) != 0){
                        array_push($filtered_by_exam_program, $lang_exam_arr[0]);
                    }
                }else if(count($item->lang_exam) == 0 && count($item->apt_exam) != 0){

                    $apt_exam_arr = [];

                    foreach($item->apt_exam as $apt ){
                        foreach( $exams as $exam){
                            if($apt->exam_id == $exam->exam_id && $exam->over_all >= $apt->over_all){
                                array_push($apt_exam_arr, $item);
                            }
                        }
                    }


                    if(count( $apt_exam_arr) != 0){
                        array_push($filtered_by_exam_program, $apt_exam_arr[0]);
                    }
                }else if(count($item->lang_exam) != 0 && count($item->apt_exam) == 0){
                    $lang_exam_arr = [];

                    foreach($item->lang_exam as $lang){
                        foreach( $exams as $exam){
                            if($lang->exam_id == $exam->exam_id && $exam->over_all >= $lang->over_all){
                                array_push($lang_exam_arr, $item);
                            }
                        }
                    }
                    if(count($lang_exam_arr) != 0){
                        array_push($filtered_by_exam_program, $lang_exam_arr[0]);
                    }
                }else if(count($item->lang_exam) == 0 && count($item->apt_exam) == 0){
                    array_push($filtered_by_exam_program, $item);
                }
            }



            foreach($filtered_by_exam_program as $item){
                $item->education_degree = EducationDegreeTranslate::where('education_degree_id', $item->program->education_degree_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->education_type;
                $item->education_language = EducationLanguageTranslate::where('education_language_id', $item->program->education_language_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->language;
                $item->currency = Currencies::where('id', $item->program->fee_currency_id)->first()->currency;
                $item->institution = InstitutionsTranslate::where('institutions_id', $item->program->institution_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->name;
                $item->program->specialty_exams = ProgramExams::where('program_id', $item->program_id)->with('exam')->get();

                foreach($item->program->specialty_exams as $exam){
                    $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $item->program_id)->get();
                }
                $item->student_id = $student;
                array_push($final_data, $item);
            }

                // array_push($test, $final_data);
            }
        return $final_data;

    }
}
