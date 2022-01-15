<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramExams extends Model
{
    use HasFactory;

    protected $table = 'program_exams';

    protected $fillable = [
        'program_id',
        'exam_id',
        'overall',
        'active'
    ];
    // protected $appends = ['sub_sections'];

    public function exam(){
        return $this->belongsTo("App\Models\Exams");
    }


    // public function getSubSectionsAttribute(){
    //     return ProgramExamsSubSections::where('exam_id', $this->exam_id)->get();
    // }
}
