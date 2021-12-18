<?php

namespace App\Services;

use App\Models\Disciplines;

/**
 * Class DisciplineService
 * @package App\Services
 */
class DisciplineService
{
    private $disciplines;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->disciplines = Disciplines::query();
    }

    public function getDisciplines(){

        // $disciplines = $this->disciplines->take($this->request->page*20)->orderBy('id', 'DESC')->get();

       if($this->request->keyword != ''){
           $this->disciplines->where(function ($q){
               $q->where('name', 'like', "%{$this->request->keyword}%")
               ->orWhere('description', 'like', "%{$this->request->keyword}%");
           });
       }
       return $this->disciplines->take($this->request->page*20)->orderBy('id', 'DESC')->get();
   }

   public function getAllDisciplines(){
        return $this->disciplines->orderBy('id', 'DESC')->get();
   }
}
