<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationDegreeRequests;
use App\Models\EducationDegree;
use Illuminate\Http\Request;

class EducationDegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EducationDegree::orderBy('id', 'DESC')->get();
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
    public function store(EducationDegreeRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $eduDeg = new EducationDegree();
            $eduDeg->fill($request->all());
            $eduDeg->save();

            return response()->json(['msg' => 'Education Degree Added Succesffully.']);
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
        return EducationDegree::where('id', $id)->first();
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
    public function update(EducationDegreeRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $discipline = EducationDegree::findOrFail($id);
            $discipline->fill($request->all());
            $discipline->save();

            return response()->json(['msg' => 'Education Degree Updated Successfully.']);
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
            $country = EducationDegree::findOrFail($id);
            $country->delete();
            return response()->json(['msg' => 'Education Degree has been deleted successfully.']);
        }
    }
}
