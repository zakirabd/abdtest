<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatesRequests;
use App\Models\States;
use App\Models\StatesTranslate;
use App\Services\StatesService;
use Illuminate\Http\Request;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use App\Helpers\UploadHelper;

class StatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->query('country_id') && $request->query('page')){
            $states = (new StatesService($request))->getStates();
        }else if($request->query('country_id')){
            $states = (new StatesService($request))->getStatesByCountry();
        }else {
            $states = (new StatesService($request))->getAllStates();
        }

        return response()->json($states);
    }
 // update active deactive state

    public function activeDeactive(Request $request, $id){
        $specialty = States::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'State updated Succesffully.']);
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
    public function store(StatesRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $state = new States();

            if(!isset($request->state_id)){

                $state->active = 1;
                $state->countries_id = $request->country_id;


                if ($request->hasFile('image_url')) {
                   
                    $state->image = UploadHelper::imageUpload($request->file('image_url'), 'uploads');

                    $ext = $request->image_url->extension();
                    $filename = rand(1, 100).time().'.'.$ext;

                    $request->image_url->storeAs('public/uploads',$filename);
                    $state->background_image = $filename;

                }
                $state->save();
            }


            $state_translate = new StatesTranslate();

            $state_translate->fill($request->all());

            if(!isset($request->state_id)){
                $state_translate->state_id = $state->id;
            }else{
                $state_translate->state_id = $request->state_id;
            }
            $state_translate->save();

            return response()->json(['msg' => 'State Added Succesffully.']);
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

        $data = StatesTranslate::where('id', $id)->with('state')->first();


        if(isset($request->add_lang)){
           $data->languages = StatesTranslate::where('state_id', $request->state_id)->get();
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
    public function update(StatesRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){

            $state_translate = StatesTranslate::findOrFail($id);
            $state = States::findOrFail($state_translate->state_id);
            if(isset($request->country_id)){
               $state->countries_id = $request->country_id;
            }

            // $state->fill($request->all());

            if ($request->hasFile('image_url')) {
                
                $state->image = UploadHelper::imageUpload($request->file('image_url'), 'uploads');

                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $state->background_image = $filename;

            }
            $state->save();
            $state_translate->name = $request->name;
            $state_translate->description = $request->description;
            $state_translate->active = $request->active;
            $state_translate->save();
            return response()->json(['msg' => 'State Updated Successfully.']);
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

            $state_translate = StatesTranslate::findOrFail($id);
            $state = States::findOrFail($state_translate->state_id);
            $check = StatesTranslate::where('state_id', $state_translate->state_id)->get();

            if(count($check) == 1){
                $state->active ='0';
                $state->save();
                // return $check;
            }

            $state_translate->delete();

            return response()->json(['msg' => 'State has been deleted successfully.']);
        }
    }
}
