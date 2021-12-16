<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    use HasFactory;

    protected $table = 'states';

    protected $fillable = [
        'name',
        'description',
        'image',
        'active',
        'lang_id',
        'country_id',
        'user_id'
    ];

    protected $hidden = [
        'user_id',
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

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function country(){
        return $this->belongsTo('App\Models\Countries');
    }
    public function city(){
        return $this->hasOne('App\Models\Cities');
    }
}
