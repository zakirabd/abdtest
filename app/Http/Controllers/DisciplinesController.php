<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisciplinesRequests;
use App\Models\Disciplines;
use App\Models\DisciplineTranslate;
use App\Services\DisciplineService;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;

class DisciplinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       if($request->query_type == 'all'){
            $disciplines = (new DisciplineService($request))->getAllDisciplines();
       }else{

            $disciplines = (new DisciplineService($request))->getDisciplines();

       }
       return response()->json($disciplines);
    }

    public function activeDeactive(Request $request, $id){
        $specialty = Disciplines::findOrFail($id);
        $specialty->update([
            'active' => $request->active
        ]);
        return response()->json(['msg' => 'Discipline updated Succesffully.']);
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
    public function store(DisciplinesRequests $request)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){
            $discipline = new Disciplines();
            $discipline->active = 1;

            if(!isset($request->discipline_id)){
               $discipline->user_id = auth()->user()->id;
               if ($request->hasFile('image')) {
                    
                    $discipline->image = UploadHelper::imageUpload($request->file('image'), 'uploads');

                }
                $discipline->save();
            }




            $discipline_translate = new DisciplineTranslate();

            if(isset($request->discipline_id)){
                $discipline_translate->discipline_id = $request->discipline_id;
            }else{
                $discipline_translate->discipline_id = $discipline->id;
            }

            $discipline_translate->fill($request->all());
            $discipline_translate->user_id = auth()->user()->id;
            $discipline_translate->save();
            return response()->json(['msg' => 'Discipline Added Succesffully.']);
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
        $data = DisciplineTranslate::where('id', $id)->with('discipline')->first();


        if(isset($request->add_lang)){
           $data->languages = DisciplineTranslate::where('discipline_id', $request->discipline_id)->get();
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
    public function update(DisciplinesRequests $request, $id)
    {
        if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'manager'){

            $discipline_translate = DisciplineTranslate::findOrFail($id);

            $discipline = Disciplines::findOrFail($discipline_translate->discipline_id);

            if($request->image != ''){
                if ($request->hasFile('image')) {
                   
                    $discipline->image = UploadHelper::imageUpload($request->file('image'), 'uploads');

                }
            }

            $discipline->save();

            $discipline_translate->name = $request->name;
            $discipline_translate->description = $request->description;
            $discipline_translate->active = $request->active;
            $discipline_translate->save();
            return response()->json(['msg' => 'Discipline Updated Successfully.']);
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
            $discipline_translate = DisciplineTranslate::findOrFail($id);
            $discipline = Disciplines::findOrFail($discipline_translate->discipline_id);
            $check = DisciplineTranslate::where('discipline_id', $discipline_translate->discipline_id)->get();

            if(count($check) == 1){
                $discipline->active ='0';
                $discipline->save();
                // return $check;
            }

            $discipline_translate->delete();

            return response()->json(['msg' => 'Discipline has been deleted successfully.']);
        }
    }
}
