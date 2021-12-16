<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $table = 'education';

    protected $fillable = [
        'type',
        'ranking',
        'name',
        'title',
        'description',
        'logo',
        'image',
        'city_id',
        'user_id',
        'lang_id',
        'active',
        'international_ranking',
        'youtube_link'
    ];

    protected $hidden = [
        'user_id',
        'logo',
        'image'
    ];
    protected $appends = ['image_full_url', 'logo_full_url', 'institutional_type'];


    public function getImageFullUrlAttribute()
    {
        return null;
    }

    public function getLogoFullUrlAttribute()
    {
        return null;
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function city(){
        return $this->belongsTo('App\Models\Cities');
        // $city = Cities::where('id', $this->city_id)->first();
        // return $city;
    }
    public function getInstitutionalTypeAttribute(){
        $type = InstitutionalTypes::where('id', $this->type)->first();
        return $type->type;
    }
}
