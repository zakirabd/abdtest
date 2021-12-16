<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialties extends Model
{
    use HasFactory;
    protected $table = 'specialties';

    protected $fillable = [
        'name',
        'user_id',
        'lang_id',
        'active',
    ];


    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
