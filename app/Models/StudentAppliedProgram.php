<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAppliedProgram extends Model
{
    use HasFactory;
    
    protected $table = "student_applied_programs";

    protected $fillable = [
        'student_id',
        'worker_id',
        'program_id',
        'status'
    ];
}
