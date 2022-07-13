<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDestinationDisciplines extends Model
{
    use HasFactory;

    protected $table = 'student_destination_disciplines';

    protected $fillable = [
        'users_id',
        'discipline_id',
        'name',
    ];
}
