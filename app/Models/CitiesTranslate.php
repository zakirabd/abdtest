<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitiesTranslate extends Model
{
    use HasFactory;

    protected $table = 'cities_translate';

    protected $fillable = [
        'city_id',
        'name',
        'description',
        'active',
        'lang_id',
        'user_id'
    ];

    public function city(){
        return $this->belongsTo('App\Models\Cities');
    }


}
