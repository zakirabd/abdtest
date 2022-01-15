<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionFaqsTranslate extends Model
{
    use HasFactory;
    protected $table = 'institution_faqs_translate';

    protected $fillable = [
        'id',
        'institution_faq_id',
        'question',
        'answer',
        'lang_id',
        'active'
    ];

    public function institution_faqs(){
        return $this->belongsTo('App\Models\InstitutionFaqs');
    }
}
