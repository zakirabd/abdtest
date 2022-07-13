<?php

namespace App\Http\Controllers;

use App\Models\Currencies;
use App\Models\EducationDegreeTranslate;
use App\Models\EducationLanguageTranslate;
use App\Models\Institutions;
use App\Models\InstitutionsTranslate;
use App\Models\program;
use App\Models\ProgramExams;
use App\Models\ProgramExamsSubSections;
use App\Models\Programs;
use App\Models\ProgramsTranslate;
use App\Models\StudentsPrograms;
use App\Models\StudentWishList;
use App\Models\User;
use App\Services\ProgramService;
use App\Services\StudentMatchingService;
use Illuminate\Http\Request;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return ProgramsTranslate::where('lang_id', 1)->with('program')->get();

        // if(!$request->query('country_id') && $request->query('page')){
        //     $states = (new ProgramService($request))->get();
        // }else if($request->query('country_id')){
        //     $states = (new ProgramService($request))->getCitiesByCountry();
        // }else if($request->query('state_id')){
        //     $states = (new ProgramService($request))->getCitiesByState();
        // }else {
        //     $states = (new ProgramService($request))->getAllCities();
        // }
        $programs = (new ProgramService($request))->getPrograms();
        return response()->json($programs);
    }

    public function activeDeactive(Request $request, $id){
        $specialty = Programs::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'Program updated Succesffully.']);
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
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'
        || auth()->user()->role == 'uni_rep'){


            if(isset($request->program_id) && $request->program_id != ''){
                $program = Programs::findOrFail($request->program_id);
            }else{
                $program = new Programs();
            }

            $program_json = json_decode($request->program_json, true);

            if($program_json != ''){
                $institution = Institutions::findOrFail($program_json['institution_id']);

                $program->country_id = $institution->country_id;
                $program->state_id = $institution->state_id;
                $program->city_id = $institution->city_id;

                $program->institution_id = $program_json['institution_id'];
                $program->fee_amount = $program_json['fee_amount'];
                $program->fee_currency_id = $program_json['fee_currency_id'];
                $program->education_degree_id = $program_json['education_degree_id'];
                $program->study_duration = $program_json['study_duration'];
                $program->start_date = $program_json['start_date'];
                $program->deadline = $program_json['deadline'];
                $program->gpa = $program_json['gpa'];
                $program->schoolarship_option = $program_json['schoolarship_option'];
                $program->video_link = $program_json['video_link'];
                $program->active = $program_json['active'];
                $program->education_language_id = $program_json['education_language_id'];
                $program->application_fee = $program_json['application_fee'];
                $program->fee_type = $program_json['fee_type'];
                $program->schoolarship_type = $program_json['schoolarship_type'];
                $program->local_exam = $program_json['local_exam'];

                $program->ib_diploma = $program_json['ib_diploma'];
                $program->a_level = $program_json['a_level'];
                $program->advanced_placement = $program_json['advanced_placement'];
                $program->ossd = $program_json['ossd'];
                $program->required_education_level = $program_json['required_education_level'];
                $program->user_id = auth()->user()->id;
            }

            $program->save();


            if(isset($request->disciplines) && $request->disciplines != ''){
                $program->program_discipline()->attach(explode(',', $request->disciplines));
            }

            $program_translate = new ProgramsTranslate();

            $program_translate->fill($request->all());


            $program_translate->program_id = $program->id;
            // $program_translate->video_link = $request->video_link;
            $program_translate->save();

            $exam_subsections = json_decode($request->json_sub_sections, true);
            $exams = json_decode($request->json_exams, true);

            if($exam_subsections != ''){
                foreach($exam_subsections as $item){

                    if(isset($item['id'])){
                        if($item['grade'] != ''){
                            $sub = ProgramExamsSubSections::findOrFail($item['id']);
                            $sub->program_id = $program->id;
                            $sub->exam_id = $item['exam_id'];
                            $sub->section_id = $item['section_id'];
                            $sub->grade = $item['grade'];
                            $sub->title = $item['title'];

                            $sub->save();

                        }else{
                            $sub = ProgramExamsSubSections::findOrFail($item['id']);
                            $sub->delete();
                        }
                    }else{
                        if($item['grade'] != ''){
                            $sub = new ProgramExamsSubSections();
                            $sub->program_id = $program->id;
                            $sub->exam_id = $item['exam_id'];
                            $sub->section_id = $item['section_id'];
                            $sub->grade = $item['grade'];
                            $sub->title = $item['title'];

                            $sub->save();
                        }
                    }
                }
            }

            if(!$exams == ''){
                foreach($exams as $item){

                    if(isset($item['id'])){
                        if($item['over_all'] != ''){
                            $exam = ProgramExams::findOrFail($item['id']);
                            $exam->over_all = $item['over_all'];
                            $exam->save();
                        }else{
                            $exam = ProgramExams::findOrFail($item['id']);
                            $exam->delete();
                        }

                    }else{
                        if( $item['over_all'] != ''){
                            $exam = new ProgramExams();
                            $exam->over_all = $item['over_all'];
                            $exam->exam_id = $item['exam_id'];
                            $exam->program_id = $program->id;
                            $exam->save();
                        }

                    }
                }
            }

            $updated_program = $program_translate->where('id',  $program_translate->id)->with('program')->first();

            $updated_program->program->specialty_exams = ProgramExams::where('program_id', $updated_program->program_id)->with('exam')->get();

            foreach($updated_program->program->specialty_exams as $exam){
                $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $updated_program->program_id)->get();
            }

            $match_programs = (new StudentMatchingService($updated_program))->matchProgramWithStudents($updated_program);
            $student_ids = [];
            foreach($match_programs as $program){
                $user = User::findOrFail($program->student_id);
                $new_program_arr = [];
                $student_programs = StudentsPrograms::where('user_id', $program->student_id)->pluck('programs_id');
                array_push( $new_program_arr, $program->program_id);
                array_push( $new_program_arr, ...$student_programs);
                $user->students_programs()->sync($new_program_arr);
            }
            foreach($match_programs as $item){
                array_push($student_ids, $item->student_id);
            }

            $other_students = User::where('role_id', '3')->whereNotIn('id', $student_ids)->get();

            foreach($other_students as $student){
                $other_student_programs = StudentsPrograms::where('user_id',$student->id)->where('programs_id', $updated_program->program_id)->first();
                if(isset($other_student_programs)){
                    $other_programs = StudentsPrograms::where('user_id', $other_student_programs->user_id)->where('programs_id','!=', $updated_program->program_id)->pluck('programs_id');
                    $user = User::findOrFail($other_student_programs->user_id);
                    $user->students_programs()->sync($other_programs);

                }
            }
            return response()->json(['msg' => 'Program Added Succesffully.', 'data' =>  $updated_program]);

            // return $request;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = ProgramsTranslate::where('id', $id)->with('program')->first();

        $data->program->specialty_exams = ProgramExams::where('program_id', $data->program_id)->with('exam')->get();

        foreach($data->program->specialty_exams as $exam){
            $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $data->program_id)->get();
        }

        if(isset($request->add_lang)){
           $data->languages = ProgramsTranslate::where('program_id', $request->program_id)->get();
        }
        return $data;
    }

    public function getProgramData(Request $request){

        $program = ProgramsTranslate::where('program_id', $request->program_id)->where('lang_id', $request->lang_id)->with('program')->first();

        if(auth()->user() != null && auth()->user()->role == 'student'){
            $student_programs = StudentsPrograms::where('user_id', auth()->user()->id)->where('programs_id', $program->program_id)->first();
            $student_wish_list = StudentWishList::where('user_id', auth()->user()->id)->where('programs_id', $program->program_id)->first();
            if(isset($student_programs)){
                $program->eligible = '1';
            }else{
                $program->eligible = '0';
            }

            if(isset($student_wish_list)){
                $program->wish_list = '1';
            }else{
                $program->wish_list = '0';
            }
        }


        $program->education_degree = EducationDegreeTranslate::where('education_degree_id', $program->program->education_degree_id)->where('lang_id', $request->lang_id ? $request->lang_id : 1)->first()->education_type;
        $program->education_language = EducationLanguageTranslate::where('education_language_id', $program->program->education_language_id)->where('lang_id', $request->lang_id ? $request->lang_id : 1)->first()->language;
        $program->currency = Currencies::where('id', $program->program->fee_currency_id)->first()->currency;
        $program->institution = InstitutionsTranslate::where('institutions_id', $program->program->institution_id)->where('lang_id', $request->lang_id ? $request->lang_id : 1)->first()->name;
        $program->program->specialty_exams = ProgramExams::where('program_id', $program->program_id)->with('exam')->get();
        $program->institution_information = Institutions::findOrFail($program->program->institution_id);
        foreach($program->program->specialty_exams as $exam){
            $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $program->program_id)->get();
        }

        return $program;

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
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'
        || auth()->user()->role == 'uni_rep'){

            $program_translate = ProgramsTranslate::findOrFail($id);
            $program = Programs::findOrFail($request->program_id);

            if(isset($request->disciplines) && $request->disciplines != ''){
                $program->program_discipline()->sync(explode(',', $request->disciplines));
            }

            $program_json = json_decode($request->program_json, true);

            if($program_json != ''){
                $institution = Institutions::findOrFail($program_json['institution_id']);

                $program->country_id = $institution->country_id;
                $program->state_id = $institution->state_id;
                $program->city_id = $institution->city_id;

                $program->institution_id = $program_json['institution_id'];
                $program->fee_amount = $program_json['fee_amount'];
                $program->fee_currency_id = $program_json['fee_currency_id'];
                $program->education_degree_id = $program_json['education_degree_id'];
                $program->study_duration = $program_json['study_duration'];
                $program->start_date = $program_json['start_date'];
                $program->deadline = $program_json['deadline'];
                $program->gpa = $program_json['gpa'];
                $program->schoolarship_option = $program_json['schoolarship_option'];
                $program->video_link = $program_json['video_link'];
                $program->active = $program_json['active'];
                $program->education_language_id = $program_json['education_language_id'];
                $program->application_fee = $program_json['application_fee'];
                $program->fee_type = $program_json['fee_type'];
                $program->schoolarship_type = $program_json['schoolarship_type'];
                $program->local_exam = $program_json['local_exam'];

                $program->ib_diploma = $program_json['ib_diploma'];
                $program->a_level = $program_json['a_level'];
                $program->advanced_placement = $program_json['advanced_placement'];
                $program->ossd = $program_json['ossd'];
                $program->required_education_level = $program_json['required_education_level'];
                // schoolarship_type
                $program->user_id = auth()->user()->id;

            }


            $program->save();
            $program_translate->fill($request->all());


            // $program_translate->video_link = $request->video_link;
            $program_translate->save();



            $exam_subsections = json_decode($request->json_sub_sections, true);
            $exams = json_decode($request->json_exams, true);

            if($exam_subsections != ''){
                foreach($exam_subsections as $item){

                    if(isset($item['id'])){
                        if($item['grade'] != ''){
                            $sub = ProgramExamsSubSections::findOrFail($item['id']);
                            $sub->program_id = $program->id;
                            $sub->exam_id = $item['exam_id'];
                            $sub->section_id = $item['section_id'];
                            $sub->grade = $item['grade'];
                            $sub->title = $item['title'];

                            $sub->save();

                        }else{
                            $sub = ProgramExamsSubSections::findOrFail($item['id']);
                            $sub->delete();
                        }
                    }else{
                        if($item['grade'] != ''){
                            $sub = new ProgramExamsSubSections();
                            $sub->program_id = $program->id;
                            $sub->exam_id = $item['exam_id'];
                            $sub->section_id = $item['section_id'];
                            $sub->grade = $item['grade'];
                            $sub->title = $item['title'];

                            $sub->save();
                        }
                    }
                }
            }

            if(!$exams == ''){
                foreach($exams as $item){

                    if(isset($item['id'])){
                        if($item['over_all'] != ''){
                            $exam = ProgramExams::findOrFail($item['id']);
                            $exam->over_all = $item['over_all'];
                            $exam->save();
                        }else{
                            $exam = ProgramExams::findOrFail($item['id']);
                            $exam->delete();
                        }

                    }else{
                        if( $item['over_all'] != ''){
                            $exam = new ProgramExams();
                            $exam->over_all = $item['over_all'];
                            $exam->exam_id = $item['exam_id'];
                            $exam->program_id = $program->id;
                            $exam->save();
                        }

                    }
                }
            }

            $updated_program = $program_translate->where('id',  $program_translate->id)->with('program')->first();

            $updated_program->program->specialty_exams = ProgramExams::where('program_id', $updated_program->program_id)->with('exam')->get();

            foreach($updated_program->program->specialty_exams as $exam){
                $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $updated_program->program_id)->get();
            }

            $match_programs = (new StudentMatchingService($updated_program))->matchProgramWithStudents($updated_program);
            $student_ids = [];
            foreach($match_programs as $program){
                $user = User::findOrFail($program->student_id);
                $new_program_arr = [];
                $student_programs = StudentsPrograms::where('user_id', $program->student_id)->pluck('programs_id');
                array_push( $new_program_arr, $program->program_id);
                array_push( $new_program_arr, ...$student_programs);
                $user->students_programs()->sync($new_program_arr);
            }
            foreach($match_programs as $item){
                array_push($student_ids, $item->student_id);
            }

            $other_students = User::where('role_id', '3')->whereNotIn('id', $student_ids)->get();

            foreach($other_students as $student){
                $other_student_programs = StudentsPrograms::where('user_id',$student->id)->where('programs_id', $updated_program->program_id)->first();
                if(isset($other_student_programs)){
                    $other_programs = StudentsPrograms::where('user_id', $other_student_programs->user_id)->where('programs_id','!=', $updated_program->program_id)->pluck('programs_id');
                    $user = User::findOrFail($other_student_programs->user_id);
                    $user->students_programs()->sync($other_programs);

                }
            }

            return response()->json(['msg' => 'Program Update Successfully.', 'data'=>$updated_program]);

        }
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
