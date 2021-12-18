<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisciplinesRequests;
use App\Models\Disciplines;
use App\Services\DisciplineService;
use Illuminate\Http\Request;

class DisciplinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if($request->query_type == 'all'){
            $disciplines = (new DisciplineService($request))->getAllDisciplines();
       }else{

            $disciplines = (new DisciplineService($request))->getDisciplines();

       }
       return response()->json($disciplines);
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

            if ($request->hasFile('image')) {
                $ext = $request->image->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image->storeAs('public/uploads',$filename);
                $discipline->image = $filename;

            }
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
        return Disciplines::where('id', $id)->first();
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

            if ($request->hasFile('image')) {
                $ext = $request->image->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image->storeAs('public/uploads',$filename);
                $discipline->image = $filename;

            }
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
            $discipline = Disciplines::findOrFail($id);
            $discipline->delete();
            return response()->json(['msg' => 'Discipline has been deleted successfully.']);
        }
    }
}
