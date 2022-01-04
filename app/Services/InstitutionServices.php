<?php

namespace App\Services;

use App\Models\CitiesTranslate;
use App\Models\CountriesTranslate;
use App\Models\InstitutionalTypeTranslate;
use App\Models\InstitutionsTranslate;

/**
 * Class InstitutionServices
 * @package App\Services
 */
class InstitutionServices
{
    private $institutions;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->institutions = InstitutionsTranslate::with('institutions')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1);

    }

    public function getInstitutions(){
        if($this->request->keyword != ''){
            $this->institutions->where('name', 'like', "%{$this->request->keyword}%");
        }

        if($this->request->filterByCountry != ''){
            $this->institutions->whereHas('institutions', function ($q){
                $q->where('country_id', $this->request->filterByCountry);
            });
        }
        if($this->request->filterByType != ''){
            $this->institutions->whereHas('institutions', function ($q){
                $q->where('type', $this->request->filterByType);
            });
        }
        if($this->request->filterByState != ''){
            $this->institutions->whereHas('institutions', function ($q){
                $q->where('state_id', $this->request->filterByState);
            });
        }

        if($this->request->filterByCity != ''){
            $this->institutions->whereHas('institutions', function ($q){
                $q->where('city_id', $this->request->filterByCity);
            });
        }
        if($this->request->query_type == 'filter'){
           $institutions_arr = $this->institutions->orderBy('id', 'DESC')->get();
        }else{
            $institutions_arr = $this->institutions->orderBy('id', 'DESC')->take($this->request->page * 20)->get();
        }


        $final_data = [];

        foreach($institutions_arr as $item){
            $type = InstitutionalTypeTranslate::where('institutional_type_id', $item->institutions->type)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->with('institutional_type')->first();
            $country = CountriesTranslate::where('countries_id', $item->institutions->country_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first();
            $city = CitiesTranslate::where('city_id', $item->institutions->city_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first();
            $item->institutions_type = $type->type;
            $item->country = $country->name;
            $item->city = $city->name;
            array_push($final_data, $item);
        }
        return $final_data;
    }

    public function getAllInstitutions(){
        return $this->institutions->orderBy('id', 'DESC')->get();
    }

    public function getOneInstitution(){
        return  $this->institutions->where('institutions_id', $this->request->institution_id)->first();
    }

}
