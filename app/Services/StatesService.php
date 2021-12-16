<?php

namespace App\Services;

use App\Models\States;

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
        $this->states = States::with('country');

    }

    public function getStates(){
        $states = $this->states->take($this->request->page*20)->orderBy('id', 'DESC')->get();
       if($this->request->keyword != ''){
           return $this->states->where('name', 'like', "%{$this->request->keyword}%")
           ->orWhere('description', 'like', "%{$this->request->keyword}%")->get();
       }else{
           return $states;
       }

   }

    public function getAllStates(){
        return $this->states->orderBy('id', 'DESC')->get();
    }


    public function getStatesByCountry(){
        return $this->states->where('country_id', $this->request->country_id)->orderBy('id', 'DESC')->get();
    }
}
