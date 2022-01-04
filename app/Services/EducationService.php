<?php

namespace App\Services;

use App\Models\Education;

/**
 * Class EducationService
 * @package App\Services
 */
class EducationService
{
    private $educations;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->educations = Education::with('city');

    }

    public function getEducations(){

        if($this->request->keyword != ''){
            $this->educations->where(function ($q){
                $q->where('name', 'like', "%{$this->request->keyword}%")
                ->orWhere('description', 'like', "%{$this->request->keyword}%");
            });
        }

        if($this->request->filterByCountry != ''){
            $this->educations->where(function ($q){
                $q->where('country_id', $this->request->filterByCountry);
            });
        }

        if($this->request->filterByState != ''){
            $this->educations->where(function ($q){
                $q->where('state_id', $this->request->filterByState);
            });
        }

        if($this->request->filterByCity != ''){
            $this->educations->where(function ($q){
                $q->where('city_id', $this->request->filterByCity);
            });
        }

        if($this->request->filterByType != ''){
            $this->educations->where(function ($q){
                $q->where('type', $this->request->filterByType);
            });
        }

        return $this->educations->take($this->request->page*20)->orderBy('id', 'DESC')->get();
   }

    public function getEducationsByCountry(){
        return $this->educations->where('country_id', $this->request->country_id)->orderBy('id', 'DESC')->get();
    }

    public function getEducationsByCity(){
        return $this->educations->where('city_id', $this->request->city_id)->orderBy('id', 'DESC')->get();
    }

    public function getEducationsBystate(){
        return $this->educations->where('state_id', $this->request->city_id)->orderBy('id', 'DESC')->get();
    }
}
