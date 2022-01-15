<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionFaqs extends Model
{
    use HasFactory;
    protected $table = 'institution_faqs';

    protected $fillable = [
        'id',
        'institution_id',
        'active',
    ];
}
