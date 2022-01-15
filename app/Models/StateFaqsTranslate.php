<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFaqsTranslate extends Model
{
    use HasFactory;

    protected $table = 'state_faqs_translate';

    protected $fillable = [
        'id',
        'state_faqs_id',
        'question',
        'answer',
        'lang_id',
        'active'
    ];

    public function state_faqs(){
        return $this->belongsTo('App\Models\StateFaqs');
    }
}
