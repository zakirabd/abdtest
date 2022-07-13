<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CountryWiseEducation;
use App\Models\Countries;
use App\Models\CountriesTranslate;
use App\Models\Institutions;
use App\Models\InstitutionsTranslate;
use App\Models\Programs;
use App\Models\ProgramsTranslate;
use App\Models\ProgramExams;
use App\Models\ProgramExamsSubSections;

class MatchingProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $destination_countries = json_decode($request->destination_countries_json);
        $destination_disciplines = json_decode($request->destination_disciplines_json);
        $exams = json_decode($request->exams_json);

        $destination_countries_array = [];
        $destination_discipline_array = [];
        
        
        foreach($destination_countries as $item){
            array_push($destination_countries_array, $item->id);
        }

        foreach($destination_disciplines as $item){
            array_push($destination_discipline_array, $item->id);
        }

        


        $suitable_countries = CountryWiseEducation::where('residental_country_id', $request->study_country_id)
                            ->whereIn('destination_country_id', $destination_countries_array)
                            ->where('residental_degree_id', $request->study_degree_id)->pluck('destination_country_id');
        $suitable_degree = CountryWiseEducation::where('residental_country_id', $request->study_country_id)
                            ->whereIn('destination_country_id', $destination_countries_array)
                            ->where('residental_degree_id', $request->study_degree_id)->pluck('destination_degree_id');


        $final_programs = [];

        if(isset($suitable_countries) && count($suitable_countries) != 0){
            
            $programs_formal = ProgramsTranslate::whereHas('program', function ($q) use ($suitable_countries, $destination_discipline_array, $suitable_degree, $request) {
                $q->whereIn('country_id', $suitable_countries)
                ->whereIn('education_degree_id', $suitable_degree)
                ->whereHas('discipline', function ($query) use ($destination_discipline_array){
                    $query->whereIn('discipline_id', $destination_discipline_array);
                })
                ->where('gpa', '<=' , $request->study_gpa);  
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
    
        if($request->study_degree_id == '1' || $request->study_degree_id == '6'){
            $programs = ProgramsTranslate::whereHas('program', function ($q) use ($destination_countries_array, $destination_discipline_array, $suitable_countries, $request) {
                $q->whereIn('country_id', $destination_countries_array)
                ->whereNotIn('country_id', $suitable_countries)
                ->whereHas('discipline', function ($query) use ($destination_discipline_array){
                    $query->whereIn('discipline_id', $destination_discipline_array);
                })
                ->where('gpa', '<=' , $request->study_gpa);

                $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '2')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '6');
                
            })
            ->with('program')
            ->get();
        }else if($request->study_degree_id == '2' || $request->study_degree_id == '2'){
            $programs = ProgramsTranslate::whereHas('program', function ($q) use ($destination_countries_array, $destination_discipline_array, $suitable_countries, $request) {
                $q->whereIn('country_id', $destination_countries_array)
                ->whereNotIn('country_id', $suitable_countries)
                ->whereHas('discipline', function ($query) use ($destination_discipline_array){
                    $query->whereIn('discipline_id', $destination_discipline_array);
                })
                ->where('gpa', '<=' , $request->study_gpa);

                $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '2')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '3')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '6');
                
            })
            ->with('program')
            ->get();
        }else if($request->study_degree_id == '5'){
            $programs = ProgramsTranslate::whereHas('program', function ($q) use ($destination_countries_array, $destination_discipline_array, $suitable_countries, $request) {
                $q->whereIn('country_id', $destination_countries_array)
                ->whereNotIn('country_id', $suitable_countries)
                ->whereHas('discipline', function ($query) use ($destination_discipline_array){
                    $query->whereIn('discipline_id', $destination_discipline_array);
                })
                ->where('gpa', '<=' , $request->study_gpa);

                $q->whereNotIn('country_id', $suitable_countries)->where('education_degree_id', '1')
                    ->orWhereNotIn('country_id', $suitable_countries)->where('education_degree_id', '5');
                
            })
            ->with('program')
            ->get();
        }else if($request->study_degree_id == '4'){
            $programs = ProgramsTranslate::whereHas('program', function ($q) use ($destination_countries_array, $destination_discipline_array, $suitable_countries, $request) {
                $q->whereIn('country_id', $destination_countries_array)
                ->whereNotIn('country_id', $suitable_countries)
                ->whereHas('discipline', function ($query) use ($destination_discipline_array){
                    $query->whereIn('discipline_id', $destination_discipline_array);
                })
                ->where('gpa', '<=' , $request->study_gpa);

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
                    foreach($exams as $exam){
                        if($apt->exam_id == $exam->name && $exam->value >= $apt->over_all){
                            array_push($apt_exam_arr, $item);
                        }
                    }
                }

                foreach($item->lang_exam as $lang){
                    foreach($exams as $exam){
                        if($lang->exam_id == $exam->name && $exam->value >= $lang->over_all){
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
                    foreach($exams as $exam){
                        if($apt->exam_id == $exam->name && $exam->value >= $apt->over_all){
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
                    foreach($exams as $exam){
                        if($lang->exam_id == $exam->name && $exam->value >= $lang->over_all){
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

    
        return $filtered_by_exam_program;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
