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
        'lang_id',
        'active'
    ];

    public function specialty(){
        return $this->hasOne("App\Models\SpecialtyExams");
    }
}
