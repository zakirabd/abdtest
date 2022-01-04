<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubsections extends Model
{
    use HasFactory;
    protected $table = 'exam_subsections';

    protected $fillable = [
        'exam_id',
        'section'
    ];

    public function specialty(){
        return $this->hasMany("App\Models\Exams");
    }

}
