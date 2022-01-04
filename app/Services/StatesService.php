<?php

namespace App\Services;

use App\Models\States;
use App\Models\StatesTranslate;

/**
 * Class StatesService
 * @package App\Services
 */
class StatesService
{
    private $states;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->states = StatesTranslate::where('lang_id', $this->request->lang_id)->with('state');

    }

    public function getStates(){

        if($this->request->keyword != ''){
            $this->states
            ->where('name', 'like', "%{$this->request->keyword}%")
            ->orWhere('description', 'like', "%{$this->request->keyword}%");
        }

        if($this->request->filterByCountry != ''){
            $this->states->whereHas('state', function ($q){
                $q->where('countries_id', $this->request->filterByCountry);
            });
        }

        return $this->states->orderBy('id', 'DESC')->take($this->request->page * 20)->get();

   }

    public function getAllStates(){
        return $this->states->orderBy('id', 'DESC')->get();
    }


    public function getStatesByCountry(){

        return $this->states
        ->whereHas('state', function ($q){
            $q->where('countries_id', $this->request->country_id);
        })
        ->orderBy('id','DESC')->get();
    }
}
