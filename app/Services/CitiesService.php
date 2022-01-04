<?php

namespace App\Services;

use App\Models\Cities;
use App\Models\CitiesTranslate;
use App\Models\CountriesTranslate;
use App\Models\StatesTranslate;

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
        $this->cities = CitiesTranslate::with('city')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1);

    }

    public function getCities(){
        // $cities = $this->cities->take($this->request->page*20)->orderBy('id', 'DESC')->get();
       if($this->request->keyword != ''){
          $this->cities
          ->where('name', 'like', "%{$this->request->keyword}%")
          ->orWhere('description', 'like', "%{$this->request->keyword}%");
       }

       if($this->request->filterByCountry != ''){
           $this->cities->whereHas('city', function ($q){
               $q->where('country_id', $this->request->filterByCountry);
           });
       }

       if($this->request->filterByState != ''){
            $this->cities->whereHas('city', function ($q){
                $q->where('state_id', $this->request->filterByState);
            });
        }

       $cities = $this->cities->take($this->request->page * 20)->orderBy('id', 'DESC')->get();

       $final_data = [];
       foreach($cities as $item){

            $state_translate = StatesTranslate::where('state_id', $item->city->state_id)->where('lang_id', $this->request->lang_id)->where('active', '1')->first();
            $item->state = $state_translate->name;

            $country_translate = CountriesTranslate::where('countries_id', $item->city->country_id)->where('lang_id', $this->request->lang_id)->where('active', '1')->first();
            $item->country = $country_translate->name;

            array_push($final_data, $item);
       }
       return $final_data;
   }

    public function getAllCities(){
        return $this->cities->orderBy('id', 'DESC')->get();
    }


    public function getCitiesByCountry(){
        return $this->cities
        ->whereHas('city', function ($q){
            $q->where('country_id', $this->request->country_id);
        })
        ->orderBy('id', 'DESC')->get();
    }

    public function getCitiesByState(){
        return $this->cities
        ->whereHas('city', function ($q){
            $q->where('state_id', $this->request->state_id);
        })
        ->orderBy('id', 'DESC')->get();
    }
}
