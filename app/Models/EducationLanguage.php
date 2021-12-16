<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLanguage extends Model
{
    use HasFactory;

    protected $table = 'education_language';

    protected $fillable = [
        'language',
        'lang_id',
        'active',
    ];

    public function specialty(){
        return $this->hasOne('App\Models\SpecialtyLanguage');
    }
}
