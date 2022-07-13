<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExams extends Model
{
    use HasFactory;

    protected $table = 'student_exams';

    protected $fillable = [
        'users_id',
        'exam_id',
        'over_all',
        'date',
    ];
}
