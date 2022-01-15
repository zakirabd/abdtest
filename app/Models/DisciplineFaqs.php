<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineFaqs extends Model
{
    use HasFactory;
    protected $table = 'discipline_faqs';

    protected $fillable = [
        'id',
        'discipline_id',
        'active',
    ];
}
