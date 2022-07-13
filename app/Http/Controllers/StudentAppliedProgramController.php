<?php

namespace App\Http\Controllers;

use App\Models\Currencies;
use App\Models\EducationDegreeTranslate;
use App\Models\EducationLanguageTranslate;
use App\Models\InstitutionsTranslate;
use App\Models\ProgramExams;
use App\Models\ProgramExamsSubSections;
use App\Models\ProgramsTranslate;
use App\Models\StudentAppliedProgram;
use App\Models\StudentWishList;
use App\Services\StudentAppliedProgramService;
use Illuminate\Http\Request;

class StudentAppliedProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->query_type) && $request->query_type == 'applied'){
            $data = (new StudentAppliedProgramService($request))->getAppliedPrograms();
            return $data;
        }else if(isset($request->query_type) && $request->query_type == 'count'){
            $data = (new StudentAppliedProgramService($request))->getAppliedProgramsCount();
            return $data;
        }

    }

    public function getStudentWishList(Request $request){
        $student_wish_list = StudentWishList::where('user_id', auth()->user()->id)->pluck('programs_id');
        $programs = ProgramsTranslate::whereIn('program_id', $student_wish_list)->with('program')->where('lang_id', $request->lang_id ? $request->lang_id : 1)->get();
        // if(isset($request->page) && $request->page != ''){
        //     $programs = $program->take($request->page* 20)->orderBy('id', 'DESC')->get();
        // }else{
        //     $programs = $program->orderBy('id', 'DESC')->get();
        // }




        foreach($programs as $item){
            $item->education_degree = EducationDegreeTranslate::where('education_degree_id', $item->program->education_degree_id)->where('lang_id', $request->lang_id ? $request->lang_id : 1)->first()->education_type;
            $item->education_language = EducationLanguageTranslate::where('education_language_id', $item->program->education_language_id)->where('lang_id', $request->lang_id ? $request->lang_id : 1)->first()->language;
            $item->currency = Currencies::where('id', $item->program->fee_currency_id)->first()->currency;
            $item->institution = InstitutionsTranslate::where('institutions_id', $item->program->institution_id)->where('lang_id', $request->lang_id ? $request->lang_id : 1)->first()->name;
            $item->program->specialty_exams = ProgramExams::where('program_id', $item->program_id)->with('exam')->get();

            foreach($item->program->specialty_exams as $exam){
                $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $item->program_id)->get();
            }
        }

        return $programs;
    }
    public function studentWishListAdd(Request $request){
        if($request->type == 'add'){
            $wish_list_check  = StudentWishList::where('user_id', $request->user_id)->where('programs_id', $request->programs_id)->first();
            if(!isset($wish_list_check)){
                $wish_list = new StudentWishList();
                $wish_list->fill($request->all());
                $wish_list->save();
            }
            return response()->json(['msg' => 'Add to wish list successfully.']);
        }else{
            $wish_list = StudentWishList::findOrFail($request->id);
            $wish_list->delete();
            return response()->json(['msg' => 'Remove from wish list successfully.']);
        }
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
        $apply_program = new StudentAppliedProgram();
        $apply_program->fill($request->all());
        $apply_program->student_id = auth()->user()->id;
        $apply_program->status = '0';
        $apply_program->save();

        return response()->json(['msg'=> 'You Aplly the program successfully.']);
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
        $student_program = StudentAppliedProgram::findOrFail($id);
        $student_program->update([
            'status' => $request->status
        ]);
        return response()->json(['msg' => 'Student Program Updated Successfully.']);
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
