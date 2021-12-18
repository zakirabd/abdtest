<?php

namespace App\Http\Controllers;

use App\Models\GradingScheme;
use Illuminate\Http\Request;

class GradingSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GradingScheme::orderBy('id', 'DESC')->get();
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
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $gradingScheme = new GradingScheme();
            $gradingScheme->fill($request->all());

            $gradingScheme->save();

            return response()->json(['msg' => 'Grading Scheme Added Successfully']);
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
        return GradingScheme::where('id', $id)->first();
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
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $gradingScheme = GradingScheme::findOrFail($id);
            $gradingScheme->fill($request->all());
            $gradingScheme->save();

            return response()->json(['msg' => 'Grading Scheme Updated Successfully.']);
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
            $gradingScheme = GradingScheme::findOrFail($id);
            $gradingScheme->delete();
            return response()->json(['msg' => 'Grading Scheme has been deleted successfully.']);
        }
    }
}
