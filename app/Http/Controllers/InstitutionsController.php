<?php

namespace App\Http\Controllers;

use App\Models\Institutions;
use App\Models\InstitutionsTranslate;
use App\Services\InstitutionServices;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;
use App\Models\InstitutionApprove;
use App\Models\InstitutionTranslateApprove;

class InstitutionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if(!$request->query('country_id') && $request->query('page')){
        //     $states = (new CitiesService($request))->getCities();
        // }else if($request->query('country_id')){
        //     $states = (new CitiesService($request))->getCitiesByCountry();
        // }else if($request->query('state_id')){
        //     $states = (new CitiesService($request))->getCitiesByState();
        // }else {
        //     $states = (new CitiesService($request))->getAllCities();
        // }
        if($request->query('query_type') && $request->query('query_type') == 'all'){
            $institutions = (new InstitutionServices($request))->getAllInstitutions();

        }else if($request->query('query_type') && $request->query('query_type') == 'one'){
            $institutions = (new InstitutionServices($request))->getOneInstitution();
        }
        // else if($request->query('query_type') && $request->query('query_type') == 'approve'){
        //     // getInstitutionApprove
        //     $institutions = (new InstitutionServices($request))->getInstitutionApprove();
        // }
        else{
            $institutions = (new InstitutionServices($request))->getInstitutions();
        }


        return response()->json($institutions);
    }


    public function activeDeactive(Request $request, $id){
        $specialty = Institutions::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'Institutions updated Succesffully.']);
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

            if(isset($request->institutions_id)){
                $institutions = Institutions::findOrFail($request->institutions_id);
            }else{
                $institutions = new Institutions();
            }



            $institutions_json = json_decode($request->institutions_json, true);

            if($institutions_json != ''){
                $institutions->type = $institutions_json['type'];
                $institutions->national_ranking = $institutions_json['national_ranking'];
                $institutions->international_ranking = $institutions_json['international_ranking'];
                $institutions->city_id = $institutions_json['city_id'];
                $institutions->country_id = $institutions_json['country_id'];
                $institutions->state_id = $institutions_json['state_id'];
                $institutions->active = $institutions_json['active'];
                $institutions->video_link = $institutions_json['video_link'];
                $institutions->user_id = auth()->user()->id;
            }
            if ($request->hasFile('image')) {

                $institutions->image = UploadHelper::imageUpload($request->file('image'), 'uploads');

                $ext = $request->image->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image->storeAs('public/uploads',$filename);
                $institutions->background_image = $filename;

            }

            if ($request->hasFile('logo')) {

                $institutions->logo = UploadHelper::imageUpload($request->file('logo'), 'uploads');

                $ext = $request->logo->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->logo->storeAs('public/uploads',$filename);
                $institutions->background_image = $filename;

            }

            $institutions->save();



            $institutions_translate = new InstitutionsTranslate();

            $institutions_translate->fill($request->all());


            $institutions_translate->institutions_id = $institutions->id;
            // $institutions_translate->video_link = $request->video_link;
            $institutions_translate->save();

            return response()->json(['msg' => 'Institutions Added Succesffully.']);
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
        $data = InstitutionsTranslate::where('id', $id)->with('institutions')->first();


        if(isset($request->add_lang)){
           $data->languages = InstitutionsTranslate::where('institutions_id', $request->institutions_id)->get();
        }
        return $data;
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
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager' ){

            $institutions_translate = InstitutionsTranslate::findOrFail($id);
            $institutions = Institutions::findOrFail($request->institutions_id);


            $institutions_json = json_decode($request->institutions_json, true);

            if($institutions_json != ''){
                $institutions->type = $institutions_json['type'];
                $institutions->national_ranking = $institutions_json['national_ranking'];
                $institutions->international_ranking = $institutions_json['international_ranking'];
                $institutions->city_id = $institutions_json['city_id'];
                $institutions->country_id = $institutions_json['country_id'];
                $institutions->state_id = $institutions_json['state_id'];
                $institutions->active = $institutions_json['active'];
                $institutions->video_link = $institutions_json['video_link'];
            }
            if ($request->hasFile('image')) {

                $institutions->image = UploadHelper::imageUpload($request->file('image'), 'uploads');
                $ext = $request->image->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image->storeAs('public/uploads',$filename);
                $institutions->background_image = $filename;

            }

            if ($request->hasFile('logo')) {

                $institutions->logo = UploadHelper::imageUpload($request->file('logo'), 'uploads');
                $ext = $request->logo->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->logo->storeAs('public/uploads',$filename);
                // $institutions->background_image = $filename;

            }

            $institutions->save();
            $institutions_translate->fill($request->all());
            // $institutions_translate->video_link = $request->video_link;
            $institutions_translate->save();

            return response()->json(['msg' => 'Institution Update Successfully.']);

        }else if(auth()->user()->role == 'uni_rep'){
            $institutions_translate = InstitutionsTranslate::findOrFail($id);
            $institutions = Institutions::findOrFail($request->institutions_id);


            $institution_translate_approve_check = InstitutionTranslateApprove::where('institutions_translate_id', $id)->first();
            if(isset($institution_translate_approve_check)){
                $institution_translate_approve = $institution_translate_approve_check;
                $institution_approve = InstitutionApprove::where('institutions_id', $institution_translate_approve_check->institutions_id)->first();
            }else {
                $institution_translate_approve = new InstitutionTranslateApprove();
                $institution_approve = new InstitutionApprove();
            }

            $institutions_json = json_decode($request->institutions_json, true);

            if($institutions_json != ''){
                $institution_approve->type = $institutions_json['type'];
                $institution_approve->national_ranking = $institutions_json['national_ranking'];
                $institution_approve->international_ranking = $institutions_json['international_ranking'];
                $institution_approve->city_id = $institutions_json['city_id'];
                $institution_approve->country_id = $institutions_json['country_id'];
                $institution_approve->state_id = $institutions_json['state_id'];
                $institution_approve->video_link = $institutions_json['video_link'];
                $institution_approve->institutions_id = $request->institutions_id;
                $institution_approve->user_id = auth()->user()->id;
            }
            if ($request->hasFile('image')) {

                $institution_approve->image = UploadHelper::imageUpload($request->file('image'), 'uploads');
                $ext = $request->image->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image->storeAs('public/uploads',$filename);
                $institution_approve->background_image = $filename;

            }else{
                $institution_approve->image =  $institutions->image;
                $institution_approve->background_image = $institutions->background_image;
            }

            if ($request->hasFile('logo')) {

                $institution_approve->logo = UploadHelper::imageUpload($request->file('logo'), 'uploads');
                $ext = $request->logo->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->logo->storeAs('public/uploads',$filename);


            }else {
                $institution_approve->logo = $institutions->logo;
            }

            $institution_approve->save();
            $institution_translate_approve->fill($request->all());
            $institution_translate_approve->institutions_translate_id = $id;
            $institution_translate_approve->user_id = auth()->user()->id;
            // $institutions_translate->video_link = $request->video_link;
            $institution_translate_approve->save();

            return response()->json(['msg' => 'Institution Update Successfully.']);
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
        if(auth()->user()->role == 'super_admin'){


            $institution_translate = InstitutionsTranslate::findOrFail($id);
            $institutions = Institutions::findOrFail($institution_translate->institutions_id);
            $check = InstitutionsTranslate::where('institutions_id', $institution_translate->institutions_id)->get();

            if(count($check) == 1){
                $institutions->active ='0';
                $institutions->save();
            }

            $institution_translate->delete();

            return response()->json(['msg' => 'Institution has been deleted successfully.']);
        }
    }
}
