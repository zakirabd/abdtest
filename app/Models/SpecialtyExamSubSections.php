<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialtyExamSubSections extends Model
{
    use HasFactory;
    protected $table = 'specialty_exam_sub_sections';

    protected $fillable = [
        'specialty_id',
        'exam_id',
        'grade'
    ];

    public function specialty(){
        return $this->hasMany('App\Models\UniSpecialties');
    }
}
