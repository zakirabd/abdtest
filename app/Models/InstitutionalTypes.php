<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionalTypes extends Model
{
    use HasFactory;

    protected $table = 'institutional_types';

    protected $fillable = [
        'active'
    ];

}
