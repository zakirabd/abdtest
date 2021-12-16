<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationDegree extends Model
{
    use HasFactory;
    protected $table = 'education_degree';

    protected $fillable = [
        'education_type',
        'user_id',
        'lang_id',
        'active',
    ];


    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
