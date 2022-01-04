<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;
    protected $table = 'countries';

    protected $fillable = [
        'image',
        'active',
    ];

    protected $hidden = [
        'image'
    ];
    protected $appends = ['image_full_url'];


    public function getImageFullUrlAttribute()
    {
        if ($this->image) {
            return asset("/storage/uploads/{$this->image}");
        } else {
            return null;
        }
    }

    public function states(){
        return $this->hasOne('App\Models\States');
    }

    public function translate(){
        return $this->hasOne('App\Models\CountriesTranslate');
    }

    public function manyTranslate(){
        return $this->hasMany(CountriesTranslate::class, 'countries_id', 'id');
    }
}
