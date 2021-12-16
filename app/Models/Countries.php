<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'active',
        'lang_id',
        'user_id'
    ];

    protected $hidden = [
        'user_id',
        'image_url'
    ];
    protected $appends = ['image_full_url'];


    public function getImageFullUrlAttribute()
    {
        if ($this->image_url) {
            return asset("/storage/uploads/{$this->image_url}");
        } else {
            return null;
        }
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function states(){
        return $this->hasOne('App\Models\States');
    }
}
