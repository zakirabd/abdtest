<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationDegree extends Model
{
    use HasFactory;
    protected $table = 'education_degree';

    protected $fillable = [

        'active',
        'image',
        'countries_id'
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
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
