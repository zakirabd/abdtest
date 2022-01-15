<?php

namespace App\Http\Controllers;

use App\Models\CountriesTranslate;
use App\Models\CountryWiseEducation;
use App\Models\EducationDegreeTranslate;
use Illuminate\Http\Request;

class CountryWiseEducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $wise_education = CountryWiseEducation::get();

        foreach($wise_education as $item){
            $item->residental_country = CountriesTranslate::where('countries_id', $item->residental_country_id)->where('lang_id', $request->lang_id ? $request->lang_id: '1')->first()->name;
            $item->residental_degree = EducationDegreeTranslate::where('education_degree_id', $item->residental_degree_id)->where('lang_id', $request->lang_id ? $request->lang_id: '1')->first()->education_type;

            $item->destination_country = CountriesTranslate::where('countries_id', $item->destination_country_id)->where('lang_id', $request->lang_id ? $request->lang_id: '1')->first()->name;
            $item->destination_degree = EducationDegreeTranslate::where('education_degree_id', $item->destination_degree_id)->where('lang_id', $request->lang_id ? $request->lang_id: '1')->first()->education_type;
        }

        return $wise_education;

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
        if(auth()->user()->role == 'super_admin'){
            $wise_education = new CountryWiseEducation();

            $wise_education->fill($request->all());

            $wise_education->save();

            return response()->json(['msg' => 'Country Wise Education added  successfully.']);
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
        $wise_education = CountryWiseEducation::findOrFail($id);

        $wise_education->residental_country = CountriesTranslate::where('countries_id', $wise_education->residental_country_id)->where('lang_id', $wise_education->lang_id ? $wise_education->lang_id: '1')->first()->name;
        $wise_education->residental_degree = EducationDegreeTranslate::where('education_degree_id', $wise_education->residental_degree_id)->where('lang_id', $wise_education->lang_id ? $wise_education->lang_id: '1')->first()->education_type;

        $wise_education->destination_country = CountriesTranslate::where('countries_id', $wise_education->destination_country_id)->where('lang_id', $wise_education->lang_id ? $wise_education->lang_id: '1')->first()->name;
        $wise_education->destination_degree = EducationDegreeTranslate::where('education_degree_id', $wise_education->destination_degree_id)->where('lang_id', $wise_education->lang_id ? $wise_education->lang_id: '1')->first()->education_type;

        return $wise_education;
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
        if(auth()->user()->role == 'super_admin'){
            $wise_education = CountryWiseEducation::findOrFail($id);

            $wise_education->fill($request->all());

            $wise_education->save();

            return response()->json(['msg' => 'Country Wise Education updated  successfully.']);
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
            $wise_education = CountryWiseEducation::findOrFail($id);

            $wise_education->delete();

            return response()->json(['msg' => 'Country Wise Education has been deleted successfully.']);
        }
    }
}
