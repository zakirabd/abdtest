<?php

namespace App\Http\Controllers;

use App\Models\EducationLanguage;
use Illuminate\Http\Request;

class EducationLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EducationLanguage::orderBy('id', 'DESC')->get();
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
            $lang = new EducationLanguage();

            $lang->fill($request->all());

            $lang->save();

            return response()->json(["msg" => "Education Language Added Successfully"]);
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
        return EducationLanguage::where('id', $id)->first();
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
            $lang = EducationLanguage::findOrFail($id);

            $lang->fill($request->all());

            $lang->save();

            return response()->json(["msg" => "Education Language Updated Successfully"]);
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
            $exam = EducationLanguage::findOrFail($id);
            $exam->delete();
            return response()->json(['msg' => 'Education Language has been deleted successfully.']);
        }
    }
}
