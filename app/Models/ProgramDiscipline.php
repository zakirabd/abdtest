<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramDiscipline extends Model
{
    use HasFactory;

    protected $table = 'program_discipline';

    protected $fillable = [
        'program_id',
        'discipline_id',

    ];
}
