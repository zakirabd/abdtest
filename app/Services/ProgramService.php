<?php

namespace App\Services;

use App\Models\Currencies;
use App\Models\EducationDegreeTranslate;
use App\Models\EducationLanguageTranslate;
use App\Models\ProgramsTranslate;

/**
 * Class ProgramService
 * @package App\Services
 */
class ProgramService
{
    private $program;
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
        $this->program = ProgramsTranslate::with('program')->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1);

    }

    public function getPrograms(){
        if($this->request->keyword != ''){
            $this->program->where('name', 'like', "%{$this->request->keyword}%");
        }

        if($this->request->filterByCountry != ''){
            $this->program->whereHas('program', function($q){
                $q->where('country_id', $this->request->filterByCountry);
            });
        }

        if($this->request->filterByState != ''){
            $this->program->whereHas('program', function($q){
                $q->where('state_id', $this->request->filterByState);
            });
        }

        if($this->request->filterByCity != ''){
            $this->program->whereHas('program', function($q){
                $q->where('city_id', $this->request->filterByCity);
            });
        }

        if($this->request->filterByStatus != ''){
            $this->program->whereHas('program', function($q){
                $q->where('active', $this->request->filterByStatus);
            });
        }

        if($this->request->filterByInstitution != ''){
            $this->program->whereHas('program', function($q){
                $q->where('institution_id', $this->request->filterByInstitution);
            });
        }

        $programs = $this->program->take($this->request->page * 20)->orderBy('id', 'DESC')->get();

        $final_data = [];

        foreach($programs as $item){
            $item->education_degree = EducationDegreeTranslate::where('education_degree_id', $item->program->education_degree_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->education_type;
            $item->education_language = EducationLanguageTranslate::where('education_language_id', $item->program->education_language_id)->where('lang_id', $this->request->lang_id ? $this->request->lang_id : 1)->first()->language;
            $item->currency = Currencies::where('id', $item->program->fee_currency_id)->first()->currency;
            array_push($final_data, $item);
        }
        return $final_data;
    }
}
