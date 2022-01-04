<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exams extends Model
{
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = [
        'exam_type',
        'type',
        'active'
    ];
    protected $appends = ['sub_sections'];

    public function specialty(){
        return $this->hasOne("App\Models\SpecialtyExams");
    }

    public function subsection(){
        return $this->belongsToMany("App\Models\Exams", "exam_subsections", "exam_id", "section");
    }

    public function getSubSectionsAttribute(){
        return ExamSubsections::where('exam_id', $this->id)->get();
    }
}
