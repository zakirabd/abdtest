<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionalTypesRequests;
use App\Models\InstitutionalTypes;
use App\Models\InstitutionalTypeTranslate;
use Illuminate\Http\Request;

class InstitutionalTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return InstitutionalTypeTranslate::with('institutional_type')->where('lang_id', $request->query('lang_id')?$request->query('lang_id'):1)->orderBy('id', 'DESC')->get();
    }


    public function activeDeactive(Request $request, $id){
        $specialty = InstitutionalTypes::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'Institutional Type updated Succesffully.']);
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

            $institutional_type = new InstitutionalTypes();

            if(!isset($request->institutional_type_id)){

                $institutional_type->active = 1;
                $institutional_type->save();
            }


            $institutional_type_translate = new InstitutionalTypeTranslate();

            $institutional_type_translate->fill($request->all());

            if(!isset($request->institutional_type_id)){
                $institutional_type_translate->institutional_type_id = $institutional_type->id;
            }else{
                $institutional_type_translate->institutional_type_id = $request->institutional_type_id;
            }
            $institutional_type_translate->save();

            return response()->json(['msg' => 'Institutional Types Added Succesffully.']);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $data = InstitutionalTypeTranslate::where('id', $id)->with('institutional_type')->first();


        if(isset($request->add_lang)){
           $data->languages = InstitutionalTypeTranslate::where('institutional_type_id', $request->institutional_type_id)->get();
        }
        return $data;
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

            $institutional_types = InstitutionalTypeTranslate::findOrFail($id);
            $institutional_type = InstitutionalTypes::findOrFail($institutional_types->institutional_type_id);

            $institutional_type->save();
            $institutional_types->type = $request->type;
            $institutional_types->active = $request->active;
            $institutional_types->save();


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
            // $education = InstitutionalTypes::findOrFail($id);
            // $education->delete();
            // return response()->json(['msg' => 'Institutional Types has been deleted successfully.']);
            $institutional_type_translate = InstitutionalTypeTranslate::findOrFail($id);
            $institutional_type = InstitutionalTypes::findOrFail($institutional_type_translate->institutional_type_id);
            $check = InstitutionalTypeTranslate::where('institutional_type_id', $institutional_type_translate->institutional_type_id)->get();

            if(count($check) == 1){
                $institutional_type->active ='0';
                $institutional_type->save();
                // return $check;
            }

            $institutional_type_translate->delete();
        }
    }
}
