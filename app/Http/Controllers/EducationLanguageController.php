<?php

namespace App\Http\Controllers;

use App\Models\EducationLanguage;
use App\Models\EducationLanguageTranslate;
use Illuminate\Http\Request;

class EducationLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return EducationLanguageTranslate::where('lang_id', $request->lang_id? $request->lang_id:1)->with('education_language')->orderBy('id', 'DESC')->get();
    }



    public function activeDeactive(Request $request, $id){
        $specialty = EducationLanguage::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'Education Language updated Succesffully.']);
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
            $education_language = new EducationLanguage();

            $education_language->active = 1;

            $education_language->save();




            $education_language_translate = new EducationLanguageTranslate();

            if(isset($request->education_language_id)){
                $education_language_translate->education_language_id = $request->education_language_id;
            }else{
                $education_language_translate->education_language_id = $education_language->id;
            }

            $education_language_translate->fill($request->all());

            $education_language_translate->save();

            return response()->json(["msg" => "Education Language Added Successfully"]);
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
        $data = EducationLanguageTranslate::where('id', $id)->with('education_language')->first();


        if(isset($request->add_lang)){
           $data->languages = EducationLanguageTranslate::where('education_language_id', $request->education_language_id)->get();
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
    public function update(Request $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $education_language_translate = EducationLanguageTranslate::findOrFail($id);

            $education_language = EducationLanguage::findOrFail($education_language_translate->education_language_id);

            $education_language->save();

            $education_language_translate->language = $request->language;
            $education_language_translate->active = $request->active;
            $education_language_translate->save();

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
            $education_language_translate = EducationLanguageTranslate::findOrFail($id);
            $education_language = EducationLanguage::findOrFail($education_language_translate->education_language_id);
            $check = EducationLanguageTranslate::where('education_language_id', $education_language_translate->education_language_id)->get();

            if(count($check) == 1){
                $education_language->active ='0';
                $education_language->save();
                // return $check;
            }

            $education_language_translate->delete();

            return response()->json(['msg' => 'Education Language has been deleted successfully.']);
        }
    }
}
