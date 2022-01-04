<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institutions extends Model
{
    use HasFactory;
    protected $table = 'institutions';

    protected $fillable = [
        'type',
        'ranking',
        'logo',
        'image',
        'city_id',
        'user_id',
        'active',
        'international_ranking',
        'video_link',
        'state_id',
        'country_id'
    ];

    protected $hidden = [
        'user_id',
        'logo',
        'image'
    ];
    protected $appends = ['image_full_url', 'logo_full_url'];


    public function getImageFullUrlAttribute()
    {
        if ($this->image) {
            return asset("/storage/uploads/{$this->image}");
        } else {
            return null;
        }
    }

    public function getLogoFullUrlAttribute()
    {
        if ($this->logo) {
            return asset("/storage/uploads/{$this->logo}");
        } else {
            return null;
        }
    }

    public function user(){

        return $this->belongsTo('App\Models\User');

    }
    public function city(){

        return $this->belongsTo('App\Models\Cities');
    }

}
