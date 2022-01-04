<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplines extends Model
{
    use HasFactory;
    protected $table = 'disciplines';

    protected $fillable = [

        'image',
        'active',
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
}
