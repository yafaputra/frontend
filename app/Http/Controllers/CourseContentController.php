<?php

namespace App\Http\Controllers;

use App\Models\CourseContent;
use App\Models\CourseDescriptions;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseContentController extends Controller
{
    public function getByCourseDescription($courseDescriptionId): JsonResponse
    {
        try {
            Log::info('Searching for course description with ID: ' . $courseDescriptionId, [
                'id' => $courseDescriptionId,
                'type' => gettype($courseDescriptionId)
            ]);

            // Validate ID
            if (!is_numeric($courseDescriptionId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID course tidak valid'
                ], 400);
            }

            $courseDescriptionId = (int) $courseDescriptionId;

            // Find course description
            $courseDescription = CourseDescriptions::find($courseDescriptionId);

            if (!$courseDescription) {
                // Log available courses for debugging
                $availableIds = CourseDescriptions::select('id', 'title')->get();
                Log::warning('Course description not found', [
                    'requested_id' => $courseDescriptionId,
                    'available_courses' => $availableIds->toArray()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Course tidak ditemukan dengan ID: ' . $courseDescriptionId,
                    'debug_info' => [
                        'available_courses' => $availableIds->map(function($course) {
                            return [
                                'id' => $course->id,
                                'title' => $course->title
                            ];
                        })
                    ]
                ], 404);
            }

            // Get course content (should be single record with JSON materials)
            $courseContent = CourseContent::where('course_description_id', $courseDescriptionId)->first();

            Log::info('Found course content', [
                'course_id' => $courseDescriptionId,
                'content_found' => $courseContent ? true : false
            ]);

            $materis = [];

            if ($courseContent && is_array($courseContent->materials)) {
                // Extract materials from JSON and format them
                $materis = collect($courseContent->materials)
                    ->map(function ($material, $index) use ($courseContent) {
                        return [
                            'id' => $index + 1, // Use index as ID since materials are in array
                            'slug' => $courseContent->slug . '-materi-' . ($material['urutan'] ?? ($index + 1)),
                            'judul' => $material['judul'] ?? 'Materi ' . ($index + 1),
                            'konten' => $material['konten'] ?? '<p>Konten akan segera tersedia.</p>',
                            'urutan' => $material['urutan'] ?? ($index + 1),
                            'course_title' => $courseContent->course_title ?: $courseDescription->title,
                            'course_description_id' => $courseContent->course_description_id
                        ];
                    })
                    ->sortBy('urutan')
                    ->values();
            }

            // Prepare response
            $response = [
                'success' => true,
                'data' => [
                    'courseDescription' => [
                        'id' => $courseDescription->id,
                        'title' => $courseDescription->title ?: 'Untitled Course',
                        'description' => $courseDescription->description ?? null,
                        'overview' => $courseDescription->description ?? null, // Add both for compatibility
                        'instructor' => $courseDescription->instructor ?? 'Unknown Instructor',
                        'instructor_name' => $courseDescription->instructor ?? 'Unknown Instructor',
                        'duration' => $courseDescription->duration ?? 0,
                        'level' => $courseDescription->level ?? 'beginner',
                        'price' => $courseDescription->price ?? 0,
                        'thumbnail' => $courseDescription->thumbnail ?? null,
                        'is_active' => $courseDescription->is_active ?? true
                    ],
                    'materis' => $materis,
                    'totalMateris' => $materis->count()
                ],
                'message' => 'Data berhasil dimuat'
            ];

            Log::info('Response prepared successfully', [
                'course_title' => $courseDescription->title,
                'materis_count' => $materis->count()
            ]);

            return response()->json($response);

        } catch (Exception $e) {
            Log::error('Error in getByCourseDescription: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'course_id' => $courseDescriptionId ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal server',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function getBySlug($slug): JsonResponse
    {
        try {
            if (empty($slug)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Slug tidak boleh kosong'
                ], 400);
            }

            // Parse slug to get course content and material info
            // Format: course-slug-materi-X
            $parts = explode('-materi-', $slug);
            if (count($parts) !== 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format slug tidak valid'
                ], 400);
            }

            $courseSlug = $parts[0];
            $materialOrder = (int) $parts[1];

            $courseContent = CourseContent::where('slug', $courseSlug)->first();

            if (!$courseContent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course dengan slug "' . $courseSlug . '" tidak ditemukan'
                ], 404);
            }

            if (!is_array($courseContent->materials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi tidak ditemukan'
                ], 404);
            }

            // Find material by urutan
            $material = collect($courseContent->materials)
                ->firstWhere('urutan', $materialOrder);

            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi dengan urutan "' . $materialOrder . '" tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $materialOrder,
                    'slug' => $slug,
                    'judul' => $material['judul'] ?? 'Untitled',
                    'konten' => $material['konten'] ?? '',
                    'urutan' => $material['urutan'] ?? $materialOrder,
                    'course_title' => $courseContent->course_title,
                    'course_description_id' => $courseContent->course_description_id
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error in getBySlug: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function getNavigation($courseDescriptionId): JsonResponse
    {
        try {
            $courseDescriptionId = (int) $courseDescriptionId;
            $courseDescription = CourseDescriptions::find($courseDescriptionId);

            if (!$courseDescription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course tidak ditemukan'
                ], 404);
            }

            $courseContent = CourseContent::where('course_description_id', $courseDescriptionId)->first();
            $navigation = collect([]);

            if ($courseContent && is_array($courseContent->materials)) {
                $navigation = collect($courseContent->materials)
                    ->map(function ($material, $index) use ($courseContent) {
                        return [
                            'id' => $index + 1,
                            'slug' => $courseContent->slug . '-materi-' . ($material['urutan'] ?? ($index + 1)),
                            'judul' => $material['judul'] ?? 'Materi ' . ($index + 1),
                            'urutan' => $material['urutan'] ?? ($index + 1)
                        ];
                    })
                    ->sortBy('urutan')
                    ->values();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'course_title' => $courseDescription->title,
                    'navigation' => $navigation,
                    'total_materis' => $navigation->count()
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error in getNavigation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Course tidak ditemukan',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function getPrevNext($slug): JsonResponse
    {
        try {
            // Parse slug to get course content and material info
            $parts = explode('-materi-', $slug);
            if (count($parts) !== 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format slug tidak valid'
                ], 400);
            }

            $courseSlug = $parts[0];
            $currentOrder = (int) $parts[1];

            $courseContent = CourseContent::where('slug', $courseSlug)->first();

            if (!$courseContent || !is_array($courseContent->materials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course tidak ditemukan'
                ], 404);
            }

            $materials = collect($courseContent->materials)->sortBy('urutan')->values();
            $currentIndex = $materials->search(function ($material) use ($currentOrder) {
                return ($material['urutan'] ?? 0) == $currentOrder;
            });

            if ($currentIndex === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi tidak ditemukan'
                ], 404);
            }

            $current = $materials[$currentIndex];
            $prev = $currentIndex > 0 ? $materials[$currentIndex - 1] : null;
            $next = $currentIndex < $materials->count() - 1 ? $materials[$currentIndex + 1] : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'current' => [
                        'id' => $currentIndex + 1,
                        'slug' => $courseSlug . '-materi-' . ($current['urutan'] ?? ($currentIndex + 1)),
                        'judul' => $current['judul'] ?? 'Untitled',
                        'urutan' => $current['urutan'] ?? ($currentIndex + 1)
                    ],
                    'prev' => $prev ? [
                        'id' => $currentIndex,
                        'slug' => $courseSlug . '-materi-' . ($prev['urutan'] ?? $currentIndex),
                        'judul' => $prev['judul'] ?? 'Untitled',
                        'urutan' => $prev['urutan'] ?? $currentIndex
                    ] : null,
                    'next' => $next ? [
                        'id' => $currentIndex + 2,
                        'slug' => $courseSlug . '-materi-' . ($next['urutan'] ?? ($currentIndex + 2)),
                        'judul' => $next['judul'] ?? 'Untitled',
                        'urutan' => $next['urutan'] ?? ($currentIndex + 2)
                    ] : null
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error in getPrevNext: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $courseContents = CourseContent::with('courseDescription')->get();

            return response()->json([
                'success' => true,
                'data' => $courseContents->map(function($content) {
                    $materialsCount = is_array($content->materials) ? count($content->materials) : 0;

                    return [
                        'id' => $content->id,
                        'slug' => $content->slug,
                        'course_title' => $content->course_title,
                        'materials_count' => $materialsCount,
                        'course_description_id' => $content->course_description_id,
                        'course_description_title' => $content->courseDescription->title ?? 'Unknown Course'
                    ];
                }),
                'total' => $courseContents->count()
            ]);

        } catch (Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $courseId = $request->get('course_id');

            $searchQuery = CourseContent::query();

            if ($courseId) {
                $searchQuery->where('course_description_id', $courseId);
            }

            $results = $searchQuery->get();

            $searchResults = collect([]);

            foreach ($results as $courseContent) {
                if (is_array($courseContent->materials)) {
                    foreach ($courseContent->materials as $index => $material) {
                        $judul = $material['judul'] ?? '';
                        $konten = $material['konten'] ?? '';

                        if (empty($query) ||
                            stripos($judul, $query) !== false ||
                            stripos($konten, $query) !== false) {

                            $searchResults->push([
                                'id' => $index + 1,
                                'slug' => $courseContent->slug . '-materi-' . ($material['urutan'] ?? ($index + 1)),
                                'judul' => $judul ?: 'Materi ' . ($index + 1),
                                'urutan' => $material['urutan'] ?? ($index + 1),
                                'course_description_id' => $courseContent->course_description_id,
                                'course_title' => $courseContent->course_title,
                                'snippet' => substr(strip_tags($konten), 0, 150) . '...'
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $searchResults->sortBy('urutan')->values(),
                'total' => $searchResults->count(),
                'query' => $query
            ]);

        } catch (Exception $e) {
            Log::error('Error in search: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pencarian',
                'error' => app()->environment('local') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}

