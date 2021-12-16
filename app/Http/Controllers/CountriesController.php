<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountriesRequests;
use App\Models\Countries;
use App\Services\CountriesService;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if($request->query('page') && $request->query('keyword')){
            $countries = (new CountriesService($request))->getCountries();
        }else {
            $countries = (new CountriesService($request))->getAllCountries();
        }

        return response()->json($countries);

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
            $country = new Countries();
            $country->fill($request->all());

            if ($request->hasFile('image_url')) {
                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $country->image_url = $filename;

            }

            $country->save();

            return response()->json(['msg' => 'Country Added Succesffully.']);
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
        return Countries::where('id', $id)->first();
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
            $country = Countries::findOrFail($id);
            $country->fill($request->all());

            if ($request->hasFile('image_url')) {
                $ext = $request->image_url->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image_url->storeAs('public/uploads',$filename);
                $country->image_url = $filename;

            }


            $country->save();

            return response()->json(['msg' => 'Country updated successfully.']);
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
            $country = Countries::findOrFail($id);
            $country->delete();
            return response()->json(['msg' => 'Country has been deleted successfully.']);
        }

    }
}
