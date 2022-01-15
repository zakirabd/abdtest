<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityFaqsTranslate extends Model
{
    use HasFactory;
    protected $table = 'city_faqs_translate';

    protected $fillable = [
        'id',
        'city_faq_id',
        'question',
        'answer',
        'lang_id',
        'active'
    ];

    public function city_faqs(){
        return $this->belongsTo('App\Models\CityFaqs');
    }
}
