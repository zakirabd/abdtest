<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionsTranslate extends Model
{
    use HasFactory;
    protected $table = 'institutions_translate';

    protected $fillable = [
        'institutions_id',
        'name',
        'description',
        'user_id',
        'lang_id',
        'video_link',
        'active'
    ];

    public function institutions(){
        return $this->belongsTo('App\Models\Institutions');
    }
}
