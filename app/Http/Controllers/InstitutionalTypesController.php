<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionalTypesRequests;
use App\Models\InstitutionalTypes;
use Illuminate\Http\Request;

class InstitutionalTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return InstitutionalTypes::orderBy('id', 'DESC')->get();
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
    public function store(InstitutionalTypesRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){

            $type = new InstitutionalTypes();
            $type->type = $request->type;
            $type->lang_id = $request->lang_id;
            $type->save();

            return response()->json(['msg' => 'Institutional Types Added Succesffully.']);
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
        return InstitutionalTypes::where('id', $id)->first();
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
    public function update(InstitutionalTypesRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $education = InstitutionalTypes::findOrFail($id);
            $education->fill($request->all());
            $education->save();

            return response()->json(['msg' => 'Institutional Types Updated Successfully.']);
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
            $education = InstitutionalTypes::findOrFail($id);
            $education->delete();
            return response()->json(['msg' => 'Institutional Types has been deleted successfully.']);
        }
    }
}
