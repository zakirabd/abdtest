<?php

namespace App\Http\Controllers;

use App\Models\CountriesTranslate;
use App\Models\EducationDegreeTranslate;
use App\Models\CountryEducationDegreeTranslate;
use App\Models\GradingScheme;
use Illuminate\Http\Request;

class GradingSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->type) && $request->type == 'education_degree' ){
           
            return $grading_scheme = GradingScheme::where('countries_id', $request->country_id)->where('education_degree_id', $request->education_degree_id)->get();
        }else if(isset($request->type) && $request->type == 'education_sub_degree'){
            return $grading_scheme = GradingScheme::where('countries_id', $request->country_id)->where('education_degree_id', $request->education_degree_id)->where('education_sub_degree_id', $request->education_sub_degree_id)->get();
        }else{
            $grading_scheme = GradingScheme::orderBy('id', 'DESC')->get();
            foreach($grading_scheme as $item){
                $item->country_name = CountriesTranslate::where('countries_id', $item->countries_id)->where('lang_id', $request->lang_id)->first()->name;
                if(isset($item->education_sub_degree_id)){
                    $item->education_degree = CountryEducationDegreeTranslate::where('country_education_degree_id', $item->education_sub_degree_id)->where('lang_id', $request->lang_id)->first()->name;
                }else{
                    $item->education_degree = EducationDegreeTranslate::where('education_degree_id', $item->education_degree_id)->where('lang_id', $request->lang_id)->first()->education_type;
                }
                
            }
            return $grading_scheme;
        }
        
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
    public function show(Request $request, $id)
    {

        $grading_scheme = GradingScheme::where('id', $id)->first();
        $grading_scheme->country_name = CountriesTranslate::where('countries_id', $grading_scheme->countries_id)->where('lang_id', $request->lang_id)->first()->name;
        $grading_scheme->education_degree = EducationDegreeTranslate::where('education_degree_id', $grading_scheme->education_degree_id)->where('lang_id', $request->lang_id)->first()->education_type;

        return $grading_scheme;
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
