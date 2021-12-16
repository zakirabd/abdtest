<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitiesRequests;
use App\Models\Cities;
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
        if(!$request->query('country_id') && $request->query('page') && $request->query('keyword')){
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
            $city->fill($request->all());
            if ($request->hasFile('image_url')) {
                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $city->image = $filename;

            }
            $city->save();

            return response()->json(['msg' => 'City Added Succesffully.']);
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
        return Cities::where('id', $id)->first();
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
            $city = Cities::findOrFail($id);
            $city->fill($request->all());
            if($request->state_id == 0){
                $city->state_id = null;
            }
            if ($request->hasFile('image_url')) {
                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $city->image = $filename;

            }
            $city->save();

            return response()->json(['msg' => 'City Updated Successfully.']);
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
            $city = Cities::findOrFail($id);
            $city->delete();
            return response()->json(['msg' => 'City has been deleted successfully.']);
        }
    }
}
