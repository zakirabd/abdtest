<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StudentRoleMainService;
use App\Models\User;
use App\Models\StudentPersonalInfo;
use App\Helpers\UploadHelper;
use App\Models\StudentDestinationCountries;
use App\Models\StudentDestinationDisciplines;
use App\Models\StudentEducationBackground;
use App\Models\StudentExams;

class StudentRoleMainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->query('query_type') && $request->query('query_type') == 'eligible_programs'){
            $data = (new StudentRoleMainService($request))->getEligiblePrograms();
        }else if($request->query('query_type') && $request->query('query_type') == 'check_complete'){
            $data = (new StudentRoleMainService($request))->getStudentProfileComplete();
        }else if($request->query('query_type') && $request->query('query_type') == 'education_background'){
            $data = (new StudentRoleMainService($request))->getStudenEducationBackground();
        }else if($request->query('query_type') && $request->query('query_type') == 'exam_score'){
            $data = (new StudentRoleMainService($request))->getStudenExamScore();
        }else if($request->query('query_type') && $request->query('query_type') == 'study_destination'){
            $data = (new StudentRoleMainService($request))->getStudenStudyDestination();
        }
        return response()->json($data);
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
        //
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
        // personal_info
        if($request->type == 'personal_info'){
            $user = User::findOrFail($id);
            $other = StudentPersonalInfo::where('users_id', $id)->first();

            if(!isset($other)){
                $other = new StudentPersonalInfo();
            }

            $user_json = json_decode($request->user, true);
            $other_json = json_decode($request->other, true);

            $user->fill($user_json);
            if(isset($user_json['password'])){
                $user->password = bcrypt($user_json['password']);
            }
            if ($request->hasFile('image')) {

                $user->image = UploadHelper::imageUpload($request->file('image'), 'uploads');
            }
            $user->save();

            $other->fill($other_json);
            $other->users_id = $id;
            $other->save();

            return response()->json(['msg' => 'Personal information data updated successfully.']);

        }else if($request->type == 'education_background'){
            $edu_background = StudentEducationBackground::findOrFail($id);
            $edu_background->fill($request->all());
            $edu_background->save();


            return response()->json(['msg' => 'Education background data updated successfully.']);
        }else if($request->type == 'exam_score'){
            $exam = json_decode($request->exam_score, true);

            foreach($exam as $item){
                if($item['id'] != ''){
                    if($item['over_all'] != ''){
                        $studentExams = StudentExams::findOrFail($item['id']);
                        $studentExams->over_all = $item['over_all'];
                        $studentExams->date = $item['date'];
                        $studentExams->save();
                    }else if($item['over_all'] == ''){
                        $studentExams = StudentExams::findOrFail($item['id']);
                        $studentExams->delete();
                    }
                }else if($item['id'] == ''){
                    if($item['over_all'] != ''){
                        $studentExams = new StudentExams();
                        $studentExams->over_all = $item['over_all'];
                        $studentExams->date = $item['date'];
                        $studentExams->exam_id = $item['exam_id'];
                        $studentExams->users_id = $item['users_id'];
                        $studentExams->save();
                    }
                }
            }
            return response()->json(['msg' => 'Exam score data updated successfully.']);
        }else if($request->type == 'education_destination'){
            $countries = json_decode($request->countries, true);

            $countries_id = [];
            foreach($countries as $country){
                if($country['id'] != ''){
                    array_push($countries_id, $country['id']);
                }

            }
            if(count($countries_id) != 0){
                $deleted_countries = StudentDestinationCountries::where('users_id', auth()->user()->id)->whereNotIn('id', $countries_id)->get();
                foreach($deleted_countries as $item){
                    $item->delete();
                }

            }

            if(isset($countries) && $countries != ''){
                foreach($countries as $item){

                    if($item['id'] == ''){
                        $destinationCountries = new StudentDestinationCountries();
                        $destinationCountries->country_id = $item['country_id'];
                        $destinationCountries->name = $item['name'];
                        $destinationCountries->users_id = $item['users_id'];
                        $destinationCountries->save();
                    }
                }
            }

            $disciplines = json_decode($request->disciplines, true);
            $disciplines_id = [];
            foreach($disciplines as $discipline){
                if($discipline['id'] != ''){
                    array_push($disciplines_id, $discipline['id']);
                }

            }
           if(count($disciplines_id) != 0){
                $deleted_disciplines = StudentDestinationDisciplines::where('users_id', auth()->user()->id)->whereNotIn('id', $disciplines_id)->get();
                foreach($deleted_disciplines as $item){
                    $item->delete();
                }
           }
            if(isset($disciplines) && $disciplines != ''){
                foreach($disciplines as $item){

                    if($item['id'] == ''){
                        $destinationDisciplines = new StudentDestinationDisciplines();
                        $destinationDisciplines->discipline_id = $item['discipline_id'];
                        $destinationDisciplines->name = $item['name'];
                        $destinationDisciplines->users_id = $item['users_id'];
                        $destinationDisciplines->save();
                    }
                }
            }

            return response()->json(['msg' => 'Study Destination data updated successfully.']);
        }

        // StudentPersonalInfo
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
