<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsPrograms extends Model
{
    use HasFactory;
    protected $table = 'students_programs';
    protected $fillable = [
        'user_id',
        'programs_id'
    ];
}
