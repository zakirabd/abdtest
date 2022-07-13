<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentWishList extends Model
{
    use HasFactory;
    protected $table = 'student_wish_lists';
    protected $fillable = [
        'user_id',
        'programs_id'
    ];
}
