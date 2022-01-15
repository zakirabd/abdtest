<?php

namespace App\Http\Controllers;

use App\Models\CountryFags;
use App\Models\CountyFagsTranslate;
use Illuminate\Http\Request;

class CountryFagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->country_id != ''){
            $faq = CountyFagsTranslate::where('lang_id', $request->lang_id)
            ->with('country_fags')
            ->whereHas('country_fags', function($q) use ($request){
                $q->where('countries_id', $request->country_id);
            });
            if($request->type != ''){
                $faq->whereHas('country_fags', function($q) use ($request){
                    $q->where('type', $request->type);
                });
            }
            return $faq->get();
        }else{
            $faq = CountyFagsTranslate::where('lang_id', $request->lang_id)
            ->with('country_fags');


            if($request->type != ''){
                $faq->whereHas('country_fags', function($q) use ($request){
                    $q->where('type', $request->type);
                });
            }
            return $faq->get();
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


            $faq = new CountryFags();
            $faq_json = json_decode($request->country_fags, true);
            $faq->countries_id = $faq_json['countries_id'];
            $faq->type = $faq_json['type'];
            $faq->active = '1';
            $faq->save();

            $faq_translate = new CountyFagsTranslate();
            $faq_translate->country_fags_id = $faq->id;
            $faq_translate->question =  $request->question;
            $faq_translate->answer =  $request->answer;
            $faq_translate->lang_id =  $request->lang_id;
            $faq_translate->active =  $request->active;

            $faq_translate->save();

            return response()->json(['msg' => 'Information added successfully']);

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
        $data = CountyFagsTranslate::where('id', $id)->with('country_fags')->first();

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
            $faq_translate = CountyFagsTranslate::findOrFail($id);
            if(isset($request->country_fags) && $request->country_fags != ''){
                $faq_json = json_decode($request->country_fags, true);
                $country = CountryFags::findOrFail($faq_json['id']);
                $country->type = $faq_json['type'];
                $country->save();
            }

            $faq_translate->fill($request->all());
            $faq_translate->save();
            return response()->json(['msg'=>'Information updated successfully.']);
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
            $faqs = CountyFagsTranslate::findOrFail($id);

            $faqs->delete();

            return response()->json(['msg' => 'Information has been deleted successfully.']);

        }

    }
}
