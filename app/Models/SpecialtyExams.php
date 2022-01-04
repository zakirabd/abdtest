<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SpecialtyExams extends Model
{
    use HasFactory;

    protected $table = 'specialty_exams';

    protected $fillable = [
        'specialty_id',
        'exam_id',
        'overall',
        'active'
    ];
    protected $appends = ['sub_sections'];

    public function exam(){
        return $this->belongsTo("App\Models\Exams");
    }


    public function getSubSectionsAttribute(){
        return SpecialtyExamSubSections::where('exam_id', $this->exam_id)->get();
    }
}
