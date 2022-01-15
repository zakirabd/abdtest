<?php

namespace App\Http\Controllers;

use App\Models\DisciplineFaqs;
use App\Models\DisciplineFaqsTranslate;
use Illuminate\Http\Request;

class DisciplineFaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return DisciplineFaqsTranslate::where('lang_id', $request->lang_id)
        ->with('discipline_faqs')
        ->whereHas('discipline_faqs', function($q) use ($request){
            $q->where('discipline_id', $request->discipline_id);
        })
        ->get();
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


            $faq = new DisciplineFaqs();
            $faq_json = json_decode($request->discipline_faqs, true);
            $faq->discipline_id = $faq_json['discipline_id'];
            $faq->active = '1';
            $faq->save();

            $faq_translate = new DisciplineFaqsTranslate();
            $faq_translate->discipline_faqs_id = $faq->id;
            $faq_translate->question =  $request->question;
            $faq_translate->answer =  $request->answer;
            $faq_translate->lang_id =  $request->lang_id;
            $faq_translate->active =  $request->active;

            $faq_translate->save();

            return response()->json(['msg' => 'FAQ added successfully']);

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
        $data = DisciplineFaqsTranslate::where('id', $id)->with('discipline_faqs')->first();

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
            $faq_translate = DisciplineFaqsTranslate::findOrFail($id);
            $faq_translate->fill($request->all());
            $faq_translate->save();
            return response()->json(['msg'=>'FAQ updated successfully.']);
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
            $faqs = DisciplineFaqsTranslate::findOrFail($id);

            $faqs->delete();

            return response()->json(['msg' => 'FAQ has been deleted successfully.']);

        }
    }
}
