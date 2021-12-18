<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamsRequests;
use App\Models\Exams;
use Illuminate\Http\Request;

class ExamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Exams::orderBy('id', 'DESC')->get();
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
    public function store(ExamsRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){

            $exam = new Exams();
            $exam->fill($request->all());

            $exam->save();

            return response()->json(['msg' => 'Exams Added Succesffully.']);
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
        return Exams::where('id', $id)->first();
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
    public function update(ExamsRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $exam = Exams::findOrFail($id);
            $exam->fill($request->all());
            $exam->save();

            return response()->json(['msg' => 'Exams Updated Successfully.']);
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
            $exam = Exams::findOrFail($id);
            $exam->delete();
            return response()->json(['msg' => 'Exams has been deleted successfully.']);
        }
    }
}
