<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialtiesRequests;
use App\Models\Specialties;
use Illuminate\Http\Request;

class SpecialtiesController extends Controller
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
    public function store(SpecialtiesRequests $request)
    {
        $specialty = new Specialties();

        $specialty->fill($request->all());
        $specialty->save();
        return response()->json(['msg' => 'Specialty added successfully']);
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
    public function update(SpecialtiesRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $specialty = Specialties::findOrFail($id);
            $specialty->fill($request->all());
            $specialty->save();

            return response()->json(['msg' => 'Specialty Updated Successfully.']);
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
            $specialty = Specialties::findOrFail($id);
            $specialty->delete();
            return response()->json(['msg' => 'Specialty has been deleted successfully.']);
        }
    }
}
