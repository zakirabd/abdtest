<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitiesRequests;
use App\Models\Cities;
use App\Models\CitiesTranslate;
use App\Services\CitiesService;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->query('country_id') && $request->query('page')){
            $states = (new CitiesService($request))->getCities();
        }else if($request->query('country_id')){
            $states = (new CitiesService($request))->getCitiesByCountry();
        }else if($request->query('state_id')){
            $states = (new CitiesService($request))->getCitiesByState();
        }else {
            $states = (new CitiesService($request))->getAllCities();
        }

        return response()->json($states);

    }

    public function activeDeactive(Request $request, $id){
        $specialty = Cities::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'City updated Succesffully.']);
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
    public function store(CitiesRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $city = new Cities();

            if(!isset($request->city_id)){

                $city->active = 1;
                $city->country_id = $request->country_id;
                $city->state_id = $request->state_id;

                if ($request->hasFile('image_url')) {
                    $ext = $request->image_url->extension();
                    $filename = rand(1, 100).time().'.'.$ext;

                    $request->image_url->storeAs('public/uploads',$filename);
                    $city->image = $filename;

                }
                $city->save();
            }


            $city_translate = new CitiesTranslate();

            $city_translate->fill($request->all());

            if(!isset($request->city_id)){
                $city_translate->city_id = $city->id;
            }else{
                $city_translate->city_id = $request->city_id;
            }
            $city_translate->save();

            return response()->json(['msg' => 'City Added Succesffully.']);

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
        // return Cities::where('id', $id)->first();
        $data = CitiesTranslate::where('id', $id)->with('city')->first();


        if(isset($request->add_lang)){
           $data->languages = CitiesTranslate::where('city_id', $request->city_id)->get();
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
    public function update(CitiesRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){

            $cities_translate = CitiesTranslate::findOrFail($id);
            $city = Cities::findOrFail($cities_translate->city_id);
            if(isset($request->country_id)){
               $city->country_id = $request->country_id;
            }
            if(isset($request->state_id)){
                $city->state_id = $request->state_id;
            }
            // $city->fill($request->all());

            if ($request->hasFile('image_url')) {
                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $city->image = $filename;

            }
            $city->save();
            $cities_translate->name = $request->name;
            $cities_translate->description = $request->description;
            $cities_translate->active = $request->active;
            $cities_translate->save();
            return response()->json(['msg' => 'city Updated Successfully.']);

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


            $city_translate = CitiesTranslate::findOrFail($id);
            $city = Cities::findOrFail($city_translate->city_id);
            $check = CitiesTranslate::where('city_id', $city_translate->city_id)->get();

            if(count($check) == 1){
                $city->active ='0';
                $city->save();
                // return $check;
            }

            $city_translate->delete();

            return response()->json(['msg' => 'City has been deleted successfully.']);
        }
    }
}
