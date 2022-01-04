<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionalTypeTranslate extends Model
{
    use HasFactory;
    protected $table = 'institutional_types_translate';

    protected $fillable = [
        'type',
        'active',
        'lang_id',
        'user_id',
        'institutional_type_id'
    ];

    public function institutional_type(){
        return $this->belongsTo('App\Models\InstitutionalTypes');
    }
}
