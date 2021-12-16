<?php

namespace App\Services;

use App\Models\Cities;

/**
 * Class CitiesService
 * @package App\Services
 */
class CitiesService
{
    private $cities;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->cities = Cities::with('country')->with('state');

    }

    public function getCities(){
        $cities = $this->cities->take($this->request->page*20)->orderBy('id', 'DESC')->get();
       if($this->request->keyword != ''){
           return $this->cities->where('name', 'like', "%{$this->request->keyword}%")
           ->orWhere('description', 'like', "%{$this->request->keyword}%")->get();
       }else{
           return $cities;
       }

   }

    public function getAllCities(){
        return $this->cities->orderBy('id', 'DESC')->get();
    }


    public function getCitiesByCountry(){
        return $this->cities->where('country_id', $this->request->country_id)->orderBy('id', 'DESC')->get();
    }

    public function getCitiesByState(){
        return $this->cities->where('state_id', $this->request->state_id)->orderBy('id', 'DESC')->get();
    }
}
