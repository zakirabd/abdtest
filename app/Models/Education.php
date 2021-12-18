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
        'youtube_link',
        'state_id',
        'country_id'
    ];

    protected $hidden = [
        'user_id',
        'logo',
        'image'
    ];
    protected $appends = ['image_full_url', 'logo_full_url', 'institutional_type', 'country', 'state'];


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
    public function getInstitutionalTypeAttribute(){
        $type = InstitutionalTypes::where('id', $this->type)->first();
        return $type->type;
    }


    public function getCountryAttribute(){

        $country = Countries::where('id', $this->country_id)->first();
        return $country;

    }
    public function getStateAttribute(){


        if($this->state_id){
            $state = States::where('id', $this->state_id)->first();
            return $state;
        }else{
            return null;
        }
    }


}
