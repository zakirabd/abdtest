<?php

namespace App\Http\Controllers;

use App\Models\InstitutionFaqs;
use App\Models\InstitutionFaqsTranslate;
use App\Models\InstitutionsTranslate;
use Illuminate\Http\Request;

class InstitutionFaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return InstitutionFaqsTranslate::where('lang_id', $request->lang_id)
        ->with('institution_faqs')
        ->whereHas('institution_faqs', function($q) use ($request){
            $q->where('institution_id', $request->institution_id);
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


            $faq = new InstitutionFaqs();
            $faq_json = json_decode($request->institution_faqs, true);
            $faq->institution_id = $faq_json['institution_id'];
            $faq->active = '1';
            $faq->save();

            $faq_translate = new InstitutionFaqsTranslate();
            $faq_translate->institution_faqs_id = $faq->id;
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
        $data = InstitutionFaqsTranslate::where('id', $id)->with('institution_faqs')->first();

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
            $faq_translate = InstitutionFaqsTranslate::findOrFail($id);
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
            $faqs = InstitutionFaqsTranslate::findOrFail($id);

            $faqs->delete();

            return response()->json(['msg' => 'FAQ has been deleted successfully.']);

        }
    }
}
