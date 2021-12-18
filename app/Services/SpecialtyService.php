<?php

namespace App\Services;

use App\Models\Specialties;

/**
 * Class SpecialtyService
 * @package App\Services
 */
class SpecialtyService
{
    private $specialty;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->specialty = Specialties::query();

    }

    public function getSpecialty(){

        if($this->request->keyword != ''){
            $this->specialty->where(function ($q){
                $q->where('name', 'like', "%{$this->request->keyword}%");
            });
        }

        return $this->specialty->take($this->request->page * 20)->orderBy('id', 'DESC')->get();
    }

    public function getAllSpecialty(){
        return $this->specialty->orderBy('id', 'DESC')->get();
    }
}
