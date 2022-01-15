<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramsTranslate extends Model
{
    use HasFactory;

    protected $table = 'programs_translate';

    protected $fillable = [
        'program_id',
        'description',
        'name',
        'program_format',
        'user_id',
        'lang_id',
        'active',
    ];

    public function program(){
        return $this->belongsTo('App\Models\Programs')->with('discipline')->with('education_degree');
    }
}
