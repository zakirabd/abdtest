<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionTranslateApprove extends Model
{
    use HasFactory;
    protected $table = 'institution_translate_approve';

    protected $fillable = [
        'institutions_translate_id',
        'institutions_id',
        'name',
        'description',
        'user_id',
        'lang_id',

    ];
    public function institutions(){
        return $this->belongsTo('App\Models\InstitutionApprove');
    }
}
