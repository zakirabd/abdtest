<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisciplinesRequests;
use App\Models\Disciplines;
use Illuminate\Http\Request;

class DisciplinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(DisciplinesRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $discipline = new Disciplines();
            $discipline->fill($request->all());
            $discipline->save();

            return response()->json(['msg' => 'Discipline Added Succesffully.']);
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
    public function update(DisciplinesRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $discipline = Disciplines::findOrFail($id);
            $discipline->fill($request->all());
            $discipline->save();

            return response()->json(['msg' => 'Discipline Updated Successfully.']);
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
            $country = Disciplines::findOrFail($id);
            $country->delete();
            return response()->json(['msg' => 'Country has been deleted successfully.']);
        }
    }
}
