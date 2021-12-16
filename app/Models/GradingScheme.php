<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingScheme extends Model
{
    use HasFactory;
    protected $table = 'grading_scheme';

    protected $fillable = [
        'type',
        'active'
    ];
}
