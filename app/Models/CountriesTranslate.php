<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountriesTranslate extends Model
{
    use HasFactory;
    protected $table = 'countries_translate';

    protected $fillable = [
        'countries_id',
        'name',
        'description',
        'active',
        'lang_id',
        'user_id'
    ];

    public function countries(){
        return $this->belongsTo('App\Models\Countries');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

}
