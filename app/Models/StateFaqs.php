<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateFaqs extends Model
{
    use HasFactory;
    protected $table = 'state_faqs';

    protected $fillable = [
        'id',
        'state_id',
        'active',
    ];
}
