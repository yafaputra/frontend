<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'instructor', 'duration', 'original', 'price', 'image', 'category'];
}
