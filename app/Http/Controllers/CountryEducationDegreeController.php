<?php

namespace App\Http\Controllers;

use App\Models\CountryEducationDegree;
use App\Models\CountryEducationDegreeTranslate;
use App\Models\CountriesTranslate;
use App\Models\EducationDegreeTranslate;
use Illuminate\Http\Request;

class CountryEducationDegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->country_id) && isset($request->education_degree_id) && $request->country_id != '' && $request->education_degree_id != '' ){
            $education_degree = CountryEducationDegreeTranslate::with('country_education_degree')
                                ->whereHas('country_education_degree', function ($q) use ($request) {
                                    $q->where('countries_id', $request->country_id)->where('education_degree_id', $request->education_degree_id);
                                }) 
                                ->where('lang_id', $request->lang_id)
                                ->get();

            foreach($education_degree as $item){
                $item->country = CountriesTranslate::where('countries_id', $item->country_education_degree->countries_id)->where('lang_id', $request->lang_id)->first()->name;
                $item->main_degree = EducationDegreeTranslate::where('education_degree_id', $item->country_education_degree->education_degree_id)->where('lang_id', $request->lang_id)->first()->education_type;
            }
        }else{
            $education_degree = CountryEducationDegreeTranslate::with('country_education_degree')->where('lang_id', $request->lang_id)->get();

            foreach($education_degree as $item){
                $item->country = CountriesTranslate::where('countries_id', $item->country_education_degree->countries_id)->where('lang_id', $request->lang_id)->first()->name;
                $item->main_degree = EducationDegreeTranslate::where('education_degree_id', $item->country_education_degree->education_degree_id)->where('lang_id', $request->lang_id)->first()->education_type;
            }
        }
       

        return $education_degree;
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
            $country_education_degree_translate = new CountryEducationDegreeTranslate();

            if(isset($request->country_education_degree_id)){
                $country_education_degree = CountryEducationDegree::findOrFail($request->country_education_degree_id);
            }else{
                $country_education_degree = new CountryEducationDegree();
            }


            $json_data = json_decode($request->country_education_degree_json);
         
            if(isset($json_data) && $json_data != ''){
                $country_education_degree->countries_id = $json_data->countries_id;
                $country_education_degree->education_degree_id = $json_data->education_degree_id;
                $country_education_degree->save();
            }

            $country_education_degree_translate->fill($request->all());
            $country_education_degree_translate->country_education_degree_id = $country_education_degree->id;
            $country_education_degree_translate->save();

            return response()->json(['msg' => "Country Education Degree Addedd Successfully."]);
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
        $data = CountryEducationDegreeTranslate::where('id', $id)->with('country_education_degree')->first();


        if(isset($request->add_lang)){
           $data->languages = CountryEducationDegreeTranslate::where('country_education_degree_id', $request->country_education_degree_id)->get();
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
            $country_education_degree_translate = CountryEducationDegreeTranslate::findOrFail($id);

            if(isset($request->country_education_degree_id)){
                $country_education_degree = CountryEducationDegree::findOrFail($request->country_education_degree_id);
            }else{
                $country_education_degree = new CountryEducationDegree();
            }


            $json_data = json_decode($request->country_education_degree_json);

            if(isset($json_data) && $json_data != ''){
                $country_education_degree->countries_id = $json_data->countries_id;
                $country_education_degree->education_degree_id = $json_data->education_degree_id;
                $country_education_degree->save();
            }

            $country_education_degree_translate->fill($request->all());
            $country_education_degree_translate->country_education_degree_id = $country_education_degree->id;
            $country_education_degree_translate->save();

            return response()->json(['msg' => "Country Education Degree Updated Successfully."]);
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
        if(auth()->user()->role == 'super_admin' ){

            $country_education_degree_translate = CountryEducationDegreeTranslate::findOrFail($id);
            $country_education_degree = CountryEducationDegree::findOrFail($country_education_degree_translate->country_education_degree_id);
            $check = CountryEducationDegreeTranslate::where('country_education_degree_id', $country_education_degree_translate->country_education_degree_id)->get();

            
            $country_education_degree_translate->delete();

            return response()->json(['msg' => 'Country Education Degree has been deleted successfully.']);
        }
    }
}
