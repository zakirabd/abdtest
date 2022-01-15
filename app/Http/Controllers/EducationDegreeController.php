<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationDegreeRequests;
use App\Models\CountriesTranslate;
use App\Models\EducationDegree;
use App\Models\EducationDegreeTranslate;
use Illuminate\Http\Request;

class EducationDegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->country_id) && $request->country_id != ''){

            $education_degree = EducationDegreeTranslate::where('lang_id', $request->lang_id? $request->lang_id:1)
            ->with('education_degree')
            ->whereHas('education_degree', function($q) use ($request){
                $q->where('countries_id', $request->country_id);
            })
            ->orderBy('id', 'DESC')
            ->get();
            $education_degree->country_name = CountriesTranslate::where('countries_id',$request->country_id)->where('lang_id', $request->lang_id? $request->lang_id:1)->first()->name;

        }else{
            $education_degree = EducationDegreeTranslate::where('lang_id', $request->lang_id? $request->lang_id:1)->with('education_degree')->orderBy('id', 'DESC')->get();
            foreach($education_degree as $item){
                $item->country_name = CountriesTranslate::where('countries_id', $item->education_degree->countries_id)->where('lang_id', $request->lang_id? $request->lang_id:1)->first()->name;
            }
        }

        return $education_degree;
    }



    public function activeDeactive(Request $request, $id){
        $specialty = EducationDegree::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'Education degree updated Succesffully.']);
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
    public function store(EducationDegreeRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $education_degree = new EducationDegree();
            $education_degree->active = 1;
            $education_degree_json = json_decode($request->education_degree_json, true);

            if(isset($education_degree_json) && $education_degree_json != ""){
                $education_degree->countries_id = $education_degree_json['countries_id'];
            }

            if(!isset($request->education_degree_id)){


               if ($request->hasFile('image')) {
                    $ext = $request->image->extension();
                    $filename = rand(1, 100).time().'.'.$ext;

                    $request->image->storeAs('public/uploads',$filename);
                    $education_degree->image = $filename;

                }
                $education_degree->save();

            }




            $education_degree_translate = new EducationDegreeTranslate();

            if(isset($request->education_degree_id)){
                $education_degree_translate->education_degree_id = $request->education_degree_id;
            }else{
                $education_degree_translate->education_degree_id = $education_degree->id;
            }

            $education_degree_translate->fill($request->all());
            $education_degree_translate->user_id = auth()->user()->id;
            $education_degree_translate->save();

            return response()->json(['msg' => 'Education Degree Added Succesffully.']);
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
        $data = EducationDegreeTranslate::where('id', $id)->with('education_degree')->first();

        $data->country_name = CountriesTranslate::where('countries_id', $data->education_degree->countries_id)->where('lang_id', $data->lang_id? $data->lang_id:1)->first()->name;

        if(isset($request->add_lang)){
           $data->languages = EducationDegreeTranslate::where('education_degree_id', $request->education_degree_id)->get();
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
    public function update(EducationDegreeRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $education_degree_translate = EducationDegreeTranslate::findOrFail($id);

            $education_degree = EducationDegree::findOrFail($education_degree_translate->education_degree_id);
            $education_degree_json = json_decode($request->education_degree_json, true);

            if(isset($education_degree_json) && $education_degree_json != ""){
                $education_degree->countries_id = $education_degree_json['countries_id'];
            }

            if($request->image != ''){
                if ($request->hasFile('image')) {
                    $ext = $request->image->extension();
                    $filename = rand(1, 100).time().'.'.$ext;

                    $request->image->storeAs('public/uploads',$filename);
                    $education_degree->image = $filename;

                }
            }

            $education_degree->save();

            $education_degree_translate->education_type = $request->education_type;
            $education_degree_translate->active = $request->active;
            $education_degree_translate->save();

            return response()->json(['msg' => 'Education Degree Updated Successfully.']);
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
            $education_degree_translate = EducationDegreeTranslate::findOrFail($id);
            $education_degree = EducationDegree::findOrFail($education_degree_translate->education_degree_id);
            $check = EducationDegreeTranslate::where('education_degree_id', $education_degree_translate->education_degree_id)->get();

            if(count($check) == 1){
                $education_degree->active ='0';
                $education_degree->save();
                // return $check;
            }

            $education_degree_translate->delete();

            return response()->json(['msg' => 'Education Degree has been deleted successfully.']);
        }
    }
}
