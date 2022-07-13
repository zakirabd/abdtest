<?php

namespace App\Services;

use App\Models\Currencies;
use App\Models\EducationDegreeTranslate;
use App\Models\EducationLanguageTranslate;
use App\Models\InstitutionsTranslate;
use App\Models\ProgramExams;
use App\Models\ProgramExamsSubSections;
use App\Models\ProgramsTranslate;
use App\Models\StudentAppliedProgram;
use App\Models\StudentWishList;
use App\Models\User;

/**
 * Class StudentAppliedProgramService
 * @package App\Services
 */
class StudentAppliedProgramService
{
    private $request;
    // private $user;
    private $applied_programs;
    private $all_programs;
    private $student_programs;

    public function __construct($request)
    {
        $this->request = $request;
        if(auth()->user()->role == 'uni_rep'){
            $this->applied_programs = StudentAppliedProgram::where('worker_id', auth()->user()->id)->where('status', $this->request->status)->get();
            $this->student_programs = StudentAppliedProgram::where('worker_id', auth()->user()->id);
        }else if(auth()->user()->role == 'student'){
            $this->applied_programs = StudentAppliedProgram::where('student_id', auth()->user()->id)->where('status', $this->request->status)->get();
            $this->student_programs = StudentAppliedProgram::where('student_id', auth()->user()->id);
        }
        // $this->user = User::findOrFail(auth()->user()->id);

        $this->all_programs = ProgramsTranslate::with('program')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1);

    }

    public function getAppliedPrograms(){
        // $programs = $this->all_programs->get();
        $final_data = [];
        $programs = [];
        foreach($this->applied_programs as $student_program){
            $student_program->program =  ProgramsTranslate::with('program')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->where('program_id', $student_program['program_id'])->first();
            $student_program->student = User::findOrFail($student_program->student_id);
            $student_program->worker = User::findOrFail($student_program->worker_id);
            array_push( $programs, $student_program);
        }

        foreach($programs as $item){
            if(isset($item->program)){
                $item->program->education_degree = EducationDegreeTranslate::where('education_degree_id', $item->program->program->education_degree_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->education_type;
                $item->program->education_language = EducationLanguageTranslate::where('education_language_id', $item->program->program->education_language_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->language;
                $item->program->currency = Currencies::where('id', $item->program->program->fee_currency_id)->first()->currency;
                $item->program->institution = InstitutionsTranslate::where('institutions_id', $item->program->program->institution_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->name;
                $item->program->program->specialty_exams = ProgramExams::where('program_id', $item->program->program_id)->with('exam')->get();

                foreach($item->program->program->specialty_exams as $exam){
                    $exam->sub_sections = ProgramExamsSubSections::where('exam_id', $exam->exam_id)->where('program_id', $item->program->program_id)->get();
                }

                array_push($final_data, $item);
            }

        }
        return $final_data;
    }

    public function getAppliedProgramsCount(){
        if(auth()->user()->role == 'uni_rep'){

            $applied_programs_count = StudentAppliedProgram::where('worker_id', auth()->user()->id)->where('status', '0')->get();
            $rejected_programs_count = StudentAppliedProgram::where('worker_id', auth()->user()->id)->where('status', '1')->get();
            $approved_programs_count = StudentAppliedProgram::where('worker_id', auth()->user()->id)->where('status', '2')->get();
            return response()->json([
                'applied_programs' => count($applied_programs_count),
                'rejected_programs' => count($rejected_programs_count),
                'approved_programs' => count($approved_programs_count)
            ]);
        }else if(auth()->user()->role == 'student'){

            $applied_programs_count = StudentAppliedProgram::where('student_id', auth()->user()->id)->where('status', '0')->get();
            $rejected_programs_count = StudentAppliedProgram::where('student_id', auth()->user()->id)->where('status', '1')->get();
            $approved_programs_count = StudentAppliedProgram::where('student_id', auth()->user()->id)->where('status', '2')->get();
            $wish_list_count = StudentWishList::where('student_id', auth()->user()->id)->get();

            return response()->json([
                'applied_programs' => count($applied_programs_count),
                'rejected_programs' => count($rejected_programs_count),
                'approved_programs' => count($approved_programs_count),
                'wish_list' => count($wish_list_count),
            ]);
        }


    }
}
