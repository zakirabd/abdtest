<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationRequests;
use App\Models\Education;
use App\Services\EducationService;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->query('country_id')){
            $education = (new EducationService($request))->getEducationsByCountry();
        }else{
            $education = (new EducationService($request))->getEducations();
        }



        return response()->json($education);

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
    public function store(EducationRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager' || auth()->user()->role == 'unirep'){

            $education = new Education();
            $education->fill($request->all());

            if ($request->hasFile('image')) {
                $ext = $request->image->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image->storeAs('public/uploads',$filename);
                $education->image = $filename;
            }


            if ($request->hasFile('logo')) {
                $ext = $request->logo->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->logo->storeAs('public/uploads',$filename);
                $education->logo = $filename;
            }

            $education->save();

            return response()->json(['msg' => 'Education Added Succesffully.']);
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
        return Education::where('id', $id)->with('city')->first();
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
    public function update(EducationRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager' || auth()->user()->role == 'unirep'){
            $education = Education::findOrFail($id);
            $education->fill($request->all());
            if ($request->hasFile('image')) {
                $ext = $request->image->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->image->storeAs('public/uploads',$filename);
                $education->image = $filename;
            }


            if ($request->hasFile('logo')) {
                $ext = $request->logo->extension();
                $filename = rand(1, 100).time().'.'.$ext;

                $request->logo->storeAs('public/uploads',$filename);
                $education->logo = $filename;
            }
            $education->save();

            return response()->json(['msg' => 'Education Updated Successfully.']);
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
            $education = Education::findOrFail($id);
            $education->delete();
            return response()->json(['msg' => 'Country has been deleted successfully.']);
        }
    }
}
