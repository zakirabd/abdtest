<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialtyLanguage extends Model
{
    use HasFactory;
    protected $table = 'specialty_language';

    protected $fillable = [
        'specialty_id',
        'language_id',
        'active'
    ];

    public function language(){
        return $this->belongsTo('App\Models\EducationLanguage');
    }
}
