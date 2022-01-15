<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountyFagsTranslate extends Model
{
    use HasFactory;

    protected $table = 'county_fags_translates';

    protected $fillable = [
        'id',
        'country_fags_id',
        'question',
        'answer',
        'lang_id',
        'active'
    ];

    public function country_fags(){
        return $this->belongsTo('App\Models\CountryFags');
    }
}
