<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatesRequests;
use App\Models\States;
use App\Services\StatesService;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->query('country_id') && $request->query('page') && $request->query('keyword')){
            $states = (new StatesService($request))->getStates();
        }else if($request->query('country_id')){
            $states = (new StatesService($request))->getStatesByCountry();
        }else {
            $states = (new StatesService($request))->getAllStates();
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
    public function store(StatesRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $state = new States();
            $state->fill($request->all());

            if ($request->hasFile('image_url')) {
                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $state->image = $filename;

            }
            $state->save();

            return response()->json(['msg' => 'State Added Succesffully.']);
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
        return States::where('id', $id)->first();
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
            $state = States::findOrFail($id);
            $state->fill($request->all());

            if ($request->hasFile('image_url')) {
                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $state->image = $filename;

            }
            $state->save();

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
            $state = States::findOrFail($id);
            $state->delete();
            return response()->json(['msg' => 'State has been deleted successfully.']);
        }
    }
}
