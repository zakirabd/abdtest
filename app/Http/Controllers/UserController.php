<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationMail;

use App\Models\StudentDestinationCountries;
use App\Models\StudentDestinationDisciplines;
use App\Models\StudentEducationBackground;
use App\Models\StudentExams;
use App\Models\StudentPersonalInfo;
use App\Services\StudentMatchingService;
use App\Services\StudentRoleMainService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->query_type && $request->query_type == 'staff'){
            $user = User::where('role_id', '!=', '1')->where('role_id', '!=', '4');

            if($request->keyword != ''){
                $user->where('first_name', 'like', "%{$request->keyword}%")
                ->orWhere('last_name', 'like', "%{$request->keyword}%")
                ->orWhere('email', 'like', "%{$request->keyword}%");
            }
            return $user->orderBy('id', 'DESC')->get();
        }else if($request->query_type && $request->query_type == 'student'){
            if(auth()->user()->role == 'student'){
                $user = User::findOrFail(auth()->user()->id);
                $other_info = StudentPersonalInfo::where('users_id', auth()->user()->id);

                return response()->json(['user' => $user, 'other' => $other_info->first()?$other_info->first():$other_info]);
            }else if(auth()->user()->role == 'uni_rep'){
                $user = User::findOrFail($request->student_id);
                $other_info = StudentPersonalInfo::where('users_id', $request->student_id);

                return response()->json(['user' => $user, 'other' => $other_info->first()?$other_info->first():$other_info]);
            }

        }

        //  return auth()->user();
    }

    public function sendConfirmationCode(Request $request){
        $six_digit_random_number = random_int(100000, 999999);
        $details = [
            "title" => "CELT Colleges",
            "body" => $six_digit_random_number
        ];

        Mail::to($request->email)->send(new ConfirmationMail($details));

        return $six_digit_random_number;
    }


    public function getCurrentUser()
    {
         return auth()->user();
        //  return auth()->user();
    }



    public function activeDeactive(Request $request, $id){
        $specialty = User::findOrFail($id);
        $specialty->update([
            'lock_status' => $request->active
        ]);
        return response()->json(['msg' => 'User updated Succesffully.']);
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
    public function studentAutoLogin($username, $password){



        try {

            $data = [
                'username' => $username,
                'password' => $password
            ];

            $user = User::where('email', $data['username'])->first();
            if(!$user || !Hash::check($data['password'], $user->password)){
                return response([
                    'message' => 'Bad creds'
                ], 401);
            }

            $token = $user->createToken('token_name')->plainTextToken;


            return 'success';

        } catch (\Exception $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter username & password.', $e->getCode());
            } elseif ($e->getCode() === 401) {
                return response()->json('Invalid Credentials. Your credentials are incorrect. Please try again with valid credentials.', $e->getCode());
            } else {
                return response()->json('Something went wrong on the server.', $e->getCode());
            }
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequests $request)
    {
        $user = new User();

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        if ($request->hasFile('image')) {

            $user->image = UploadHelper::imageUpload($request->file('image'), 'uploads');
        }
        $user->role_id = $request->role_id;
        $user->lock_status = 0;
        $user->password = bcrypt($request->password);
        $user->save();



        $destination_countries_json = json_decode($request->destination_countries_json, true);

        if(isset($destination_countries_json)){
            foreach($destination_countries_json as $item){

                $destination_countries = new StudentDestinationCountries();
                $destination_countries->users_id = $user->id;
                $destination_countries->country_id = $item['id'];
                $destination_countries->name = $item['name'];
                $destination_countries->save();

            }
        }



        $destination_disciplines_json = json_decode($request->destination_disciplines_json, true);
        if(isset($destination_disciplines_json)){
            foreach($destination_disciplines_json as $item){

                $destination_disciplines = new StudentDestinationDisciplines();
                $destination_disciplines->users_id = $user->id;
                $destination_disciplines->discipline_id = $item['id'];
                $destination_disciplines->name = $item['name'];
                $destination_disciplines->save();

            }
        }

        if( isset($request->study_country_id) && isset($request->study_degree_id) && isset($request->study_gpa)){
            $study_background = new StudentEducationBackground();

            $study_background->users_id = $user->id;
            $study_background->study_country_id = $request->study_country_id;
            $study_background->study_degree_id = $request->study_degree_id;
            $study_background->study_gpa = $request->study_gpa;

            $study_background->save();
        }





        $student_exams_json = json_decode($request->exams_json, true);
        if(isset( $student_exams_json)){
            foreach($student_exams_json as $item){
            $student_exams = new StudentExams();
            $student_exams->users_id = $user->id;
            $student_exams->exam_id = $item['exam_id'];
            $student_exams->over_all = $item['over_all'];
            $student_exams->save();
        }
        }

        if($user->role_id == '3'){
            // $check_login = $this->studentAutoLogin($request->email,$request->password);

            $eligible_program = (new StudentMatchingService($user))->getEligiblePrograms();



        }
        $eligible_program_id = [];
        foreach($eligible_program as $item){
            array_push($eligible_program_id, $item->program_id);
        }
        $user->students_programs()->attach($eligible_program_id);
        return $eligible_program;
        // return response()->json(['msg' => 'User Create Successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::findOrFail($id);
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
        $user = User::findOrFail($id);

        $user->fill($request->all());
        if($user->password != ''){

            $user->password = bcrypt($request->password);

        }

        if ($request->hasFile('image')) {

            $user->image = UploadHelper::imageUpload($request->file('image'), 'uploads');
        }
        $user->save();
        return response()->json(['msg' => 'User Updated Successfully']);
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
