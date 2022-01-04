<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatesTranslate extends Model
{
    use HasFactory;
    protected $table = 'state_translate';
    protected $fillable = [
        'state_id',
        'name',
        'description',
        'lang_id',
        'active',
        'user_id'
    ];

    public function state(){
        return $this->belongsTo('App\Models\States');
    }
}
