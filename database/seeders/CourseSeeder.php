<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        Course::create([
            'title' => 'Full Stack Mobile Development',
            'instructor' => 'John Doe',
            'duration' => '10 jam 30 menit • 25 video',
            'original' => '150000',
            'price' => '120000',
            'image' => '/image/devfest-stockholm.png',
            'category' => 'Fullstack Development'
        ]);

        Course::create([
            'title' => 'Fundamental ReactJS',
            'instructor' => 'Jane Smith',
            'duration' => '6 jam 15 menit • 15 video',
            'original' => '100000',
            'price' => '80000',
            'image' => '/image/devfest-stockholm.png',
            'category' => 'Web Programming'
        ]);
    }
}


