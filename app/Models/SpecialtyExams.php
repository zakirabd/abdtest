<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function exam(){
        return $this->belongsTo("App\Models\Exams");
    }
}
