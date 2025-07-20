<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseDescriptions;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // Ambil semua course dengan relasi courseDescription
        $courses = Course::with('courseDescription')->get();

        // Transform data untuk sesuai dengan format yang diharapkan frontend
        $transformedCourses = $courses->map(function($course) {
            if (!$course->courseDescription) {
                return null;
            }

            return [
                'id' => $course->id,
                'title' => $course->courseDescription->title,
                'instructor' => $course->courseDescription->instructor_name,
                'duration' => $course->courseDescription->duration . ' hours',
                'original' => number_format($course->courseDescription->price, 0, ',', '.'),
                'price' => number_format($course->courseDescription->price_discount ?? $course->courseDescription->price, 0, ',', '.'),
                'image' => $course->courseDescription->image_url ? '/storage/' . $course->courseDescription->image_url : '/images/default.jpg',
                'category' => $course->courseDescription->tag ?? 'Umum'
            ];
        })->filter(); // Hapus null values

        return response()->json($transformedCourses->values());
    }

    public function show($id)
    {
        $course = Course::with('courseDescription')->find($id);

        if (!$course || !$course->courseDescription) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $flattenedData = [
            'id' => $course->id,
            'title' => $course->courseDescription->title,
            'tag' => $course->courseDescription->tag,
            'overview' => $course->courseDescription->overview,
            'image_url' => $course->courseDescription->image_url,
            'thumbnail' => $course->courseDescription->thumbnail,
            'price' => $course->courseDescription->price_discount ?? $course->courseDescription->price,
            'price_discount' => $course->courseDescription->price_discount,
            'instructor_name' => $course->courseDescription->instructor_name,
            'instructor_position' => $course->courseDescription->instructor_position,
            'instructor_image_url' => $course->courseDescription->instructor_image_url,
            'video_count' => $course->courseDescription->video_count,
            'duration' => $course->courseDescription->duration,
            'features' => $course->courseDescription->features ?? [],
        ];

        return response()->json($flattenedData);
    }
}
