<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Roles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firts_name',
        'last_name',
        'email',
        'phone_number',
        'role_id',
        'image',
        'password',
        'lock_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'role_id',
        'image'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = ['full_name', 'image_full_url', 'role'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function getImageFullUrlAttribute()
    {
        return null;
    }
    public function getRoleAttribute()
    {
        $role = Roles::where('id', $this->role_id)->select('name')->first();
        return $role->name;
    }

    public function countries(){
        return $this->hasOne('App\Models\Countries');
    }

    public function states(){
        return $this->hasOne('App\Models\States');
    }
    public function education(){
        return $this->hasOne('App\Models\Education');
    }

    public function disciplines(){
        return $this->hasOne('App\Models\Disciplines');
    }

    public function educationDegree(){
        return $this->hasOne('App\Models\EducationDegree');
    }

    public function specialies(){
        return $this->hasOne('App\Models\Specialties');
    }
}
