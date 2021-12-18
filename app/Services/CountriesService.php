<?php

namespace App\Services;

use App\Models\Countries;

/**
 * Class CountriesService
 * @package App\Services
 */
class CountriesService
{
    private $countries;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->countries = Countries::query();
    }

    public function getCountries(){
         $countries = $this->countries->take($this->request->page*20)->orderBy('id', 'DESC')->get();
        // return  Countries::get();
        if($this->request->keyword != ''){
            return $this->countries->where('name', 'like', "%{$this->request->keyword}%")
            ->orWhere('description', 'like', "%{$this->request->keyword}%")->get();
        }else{
            return $countries;
        }

    }

    public function getAllCountries(){
        return $this->countries->orderBy('id', 'DESC')->get();
    }
}
