<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLanguageTranslate extends Model
{
    use HasFactory;

    protected $table = 'education_language_translate';

    protected $fillable = [
        'education_language_id',
        'language',
        'lang_id',
        'active',
    ];

    public function education_language(){
        return $this->belongsTo('App\Models\EducationLanguage');
    }
}
