<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    use HasFactory;

    protected $table = 'course_content';

    protected $fillable = [
        'slug',
        'judul',
        'konten',
        'urutan',
        'course_description_id',
        'course_title'
    ];

    protected $casts = [
        'urutan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan CourseDescriptions
    public function courseDescriptions()
    {
        return $this->belongsTo(CourseDescriptions::class, 'course_description_id');
    }
}
