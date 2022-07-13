<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;
    protected $table = 'cities';

    protected $fillable = [

        'image',
        'active',
        'country_id',
        'state_id',
        'background_image'
    ];

    protected $hidden = [
        'user_id',
        'image',
        'background_image'
    ];
    protected $appends = ['image_full_url', 'background_full_url'];


    public function getImageFullUrlAttribute()
    {
        if ($this->image) {
            return asset("/storage/uploads/{$this->image}");
        } else {
            return null;
        }
    }

    public function getBackgroundFullUrlAttribute()
    {
        if ($this->background_image) {
            return asset("/storage/uploads/{$this->background_image}");
        } else {
            return null;
        }
    }

    public function country(){
        return $this->belongsTo('App\Models\Countries');
    }
    public function state(){
        return $this->belongsTo('App\Models\States');
    }


}
