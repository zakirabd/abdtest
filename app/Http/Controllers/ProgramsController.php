<?php

namespace App\Http\Controllers;

use App\Models\Institutions;
use App\Models\program;
use App\Models\ProgramExams;
use App\Models\ProgramExamsSubSections;
use App\Models\Programs;
use App\Models\ProgramsTranslate;
use App\Services\ProgramService;
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
        || auth()->user()->role == 'unirep'){


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

                $program->ib_diploma = $program_json['ib_diploma'];
                $program->a_level = $program_json['a_level'];
                $program->advanced_placement = $program_json['advanced_placement'];
                $program->ossd = $program_json['ossd'];
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
        return ProgramsTranslate::where('program_id', $request->program_id)->where('lang_id', $request->lang_id)->with('program')->first();
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

                $program->ib_diploma = $program_json['ib_diploma'];
                $program->a_level = $program_json['a_level'];
                $program->advanced_placement = $program_json['advanced_placement'];
                $program->ossd = $program_json['ossd'];
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
