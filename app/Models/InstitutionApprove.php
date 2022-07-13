<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionApprove extends Model
{
    use HasFactory;

    protected $table = 'institution_approve';

    protected $fillable = [
        'institutions_id',
        'type',
        'ranking',
        'logo',
        'image',
        'city_id',
        'user_id',
        'international_ranking',
        'video_link',
        'state_id',
        'country_id',
        'background_image'
    ];

    protected $hidden = [
        'user_id',
        'logo',
        'image',
        'background_image'
    ];
    protected $appends = ['image_full_url', 'logo_full_url', 'background_full_url'];


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

    public function getBackgroundFullUrlAttribute()
    {
        if ($this->background_image) {
            return asset("/storage/uploads/{$this->background_image}");
        } else {
            return null;
        }
    }

}
