<?php

namespace App\Services;

use App\Models\Countries;
use App\Models\CountriesTranslate;

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
        $this->countries = CountriesTranslate::query();
    }

    public function getCountries(){

        if($this->request->keyword != ''){
            $this->countries
            ->where('lang_id', $this->request->lang_id)
            ->where('name', 'like', "%{$this->request->keyword}%")

            ->orWhere('lang_id', $this->request->lang_id)
            ->Where('description', 'like', "%{$this->request->keyword}%");
        }


        return $this->countries->where('lang_id', $this->request->lang_id)->with('countries')->orderBy('id', 'DESC')->take($this->request->page * 20)->get();

    }

    public function getAllCountries(){
        return $this->countries->where('lang_id', 1)->with('countries')->orderBy('id', 'DESC')->get();
    }
}
