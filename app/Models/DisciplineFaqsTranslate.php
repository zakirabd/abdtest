<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineFaqsTranslate extends Model
{
    use HasFactory;

    protected $table = 'discipline_faqs_translate';

    protected $fillable = [
        'id',
        'discipline_fags_id',
        'question',
        'answer',
        'lang_id',
        'active'
    ];

    public function discipline_faqs(){
        return $this->belongsTo('App\Models\DisciplineFaqs');
    }
}
