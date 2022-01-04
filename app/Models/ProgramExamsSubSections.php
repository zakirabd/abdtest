<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramExamsSubSections extends Model
{
    use HasFactory;

    protected $table = 'program_exam_subsections';

    protected $fillable = [
        'program_id',
        'exam_id',
        'section_id',
        'grade',
        'title'
    ];

    public function programs(){
        return $this->hasMany('App\Models\Programs');
    }
}
