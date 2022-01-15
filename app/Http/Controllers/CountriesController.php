<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountriesRequests;
use App\Models\Countries;
use App\Services\CountriesService;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;
use App\Models\CountriesTranslate;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if($request->query('page') && $request->query('page') != ''){
            $countries = (new CountriesService($request))->getCountries();
        }else{
            $countries = (new CountriesService($request))->getAllCountries();
        }


        return response()->json($countries);

    }
    // update active deactive countries

    public function activeDeactive(Request $request, $id){
        $specialty = Countries::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'Countries updated Succesffully.']);
    }
    // get one country data

    public function getCountryData(Request $request){
        $country = CountriesTranslate::where('countries_id', $request->country_id)->where('lang_id', $request->lang_id)->with('countries')->first();
        return $country;
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
    public function store(CountriesRequests $request)
    {
       if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
           if(!isset($request->countries)){
                $country = new Countries();

                if ($request->hasFile('image_url')) {
                    $ext = $request->image_url->extension();
                    $filename = rand(1, 100).time().'.'.$ext;

                    $request->image_url->storeAs('public/uploads',$filename);
                    $country->image = $filename;

                }

                $country->active = '1';
                $country->save();
           }


            $country_translate = new CountriesTranslate();

            if(isset($request->countries)){
                $country_translate->countries_id = $request->countries_id;
            }else{
                $country_translate->countries_id = $country->id;
            }


            $country_translate->name = $request->name;
            $country_translate->description =$request->description;
            $country_translate->lang_id = $request->lang_id;
            $country_translate->active = $request->active;
            $country_translate->user_id = auth()->user()->id;
            $country_translate->save();

            return response()->json(['msg' => 'Country Added Succesffully.']);

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
        $data = CountriesTranslate::where('id', $id)->with('countries')->first();


        if(isset($request->add_lang)){
           $data->languages = CountriesTranslate::where('countries_id', $request->country_id)->get();
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
    public function update(CountriesRequests $request, $id)
    {
       if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){

            $translate = CountriesTranslate::findOrFail($id);
            $country = Countries::findOrFail($request->countries_id);

            // $country->fill($request->all());


            if ($request->hasFile('image_url')) {
                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $country->image = $filename;

            }
            $country->save();

            $translate->name = $request->name;
            $translate->description = $request->description;
            $translate->active = $request->active;
            $translate->save();

            return $translate;
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(auth()->user()->role == 'super_admin'){


            $country_translate = CountriesTranslate::findOrFail($id);
            $country = Countries::findOrFail($country_translate->countries_id);
            $check = CountriesTranslate::where('countries_id', $country_translate->countries_id)->get();

            if(count($check) == 1){
                $country->active ='0';
                $country->save();
                // return $check;
            }

            $country_translate->delete();

            return response()->json(['msg' => 'Country has been deleted successfully.']);
        }

    }
}
