<?php

namespace App\Http\Controllers;

use App\Models\SpecialtyExams;
use App\Models\SpecialtyLanguage;
use App\Models\UniSpecialties;
use Illuminate\Http\Request;

class UniSpecialtiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $specialty = UniSpecialties::get();
        return $specialty;
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
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager' || auth()->user()->role == 'manager'){

            $specialty = new UniSpecialties();
            $specialty->description = $request->description;
            $specialty->specialty_id = $request->specialty_id;
            $specialty->discipline_id = $request->discipline_id;
            $specialty->university_id = $request->university_id;
            $specialty->fee_amount = $request->fee_amount;
            $specialty->fee_currency_id = $request->fee_currency_id;
            $specialty->education_degree_id = $request->education_degree_id;
            $specialty->study_duration = $request->study_duration;
            $specialty->grading_scheme_id = $request->grading_scheme_id;
            $specialty->program_format = $request->program_format;
            $specialty->start_date = $request->start_date;
            $specialty->deadline = $request->deadline;
            $specialty->gpa = $request->gpa;
            $specialty->schoolarship_option = $request->schoolarship_option;
            $specialty->user_id = $request->user_id;
            $specialty->lang_id = $request->lang_id;
            $specialty->active = $request->active;
            $specialty->save();

            // $exams = json_decode($request->exams, true);
            // $languages = json_decode($request->language, true);

            // foreach($exams as $item){
            //     $exam = new SpecialtyExams();
            //     $exam->fill($item);
            //     $exam->over_all = 6;
            //     $exam->specialty_id = $specialty->id;
            //     $exam->save();
            // }

            // foreach($languages as $item){
            //     $language = new SpecialtyLanguage();
            //     $language->fill($item);
            //     $language->specialty_id = $specialty->id;
            //     $language->active = 1;
            //     $language->save();
            // }

          return response()->json(['msg' => 'Specialty Added Succesffully.']);


        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return UniSpecialties::where('id', $id)->first();
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
