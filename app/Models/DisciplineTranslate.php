<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineTranslate extends Model
{
    use HasFactory;
    protected $table = 'disciplines_translate';

    protected $fillable = [

        'name',
        'description',
        'discipline_id',
        'lang_id',
        'active'
    ];

    public function discipline(){
        return $this->belongsTo('App\Models\Disciplines');
    }
}
