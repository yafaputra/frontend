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

            // Get course contents
            $courseContents = CourseContent::where('course_description_id', $courseDescriptionId)
                ->orderBy('urutan', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            Log::info('Found course contents', [
                'course_id' => $courseDescriptionId,
                'content_count' => $courseContents->count()
            ]);

            // Map course contents to expected format
            $materis = $courseContents->map(function ($content, $index) {
                return [
                    'id' => $content->id,
                    'slug' => $content->slug ?: 'materi-' . $content->id,
                    'judul' => $content->judul ?: 'Materi ' . ($index + 1),
                    'konten' => $content->konten ?: '<p>Konten akan segera tersedia.</p>',
                    'urutan' => $content->urutan ?: ($index + 1),
                    'course_title' => $content->course_title ?: $courseDescription->title,
                    'course_description_id' => $content->course_description_id
                ];
            });

            // Prepare response
            $response = [
                'success' => true,
                'data' => [
                    'courseDescription' => [
                        'id' => $courseDescription->id,
                        'title' => $courseDescription->title ?: 'Untitled Course',
                        'description' => $courseDescription->overview ?? null,
                        'overview' => $courseDescription->overview ?? null, // Add both for compatibility
                        'tag' => $courseDescription->tag ?? 'General',
                        'instructor_name' => $courseDescription->instructor_name ?? 'Unknown Instructor',
                        'duration' => $courseDescription->duration ?? 0,
                        'video_count' => $courseDescription->video_count ?? 0,
                        'image_url' => $courseDescription->image_url ?? null,
                        'price' => $courseDescription->price ?? 0
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

            $courseContent = CourseContent::where('slug', $slug)->first();

            if (!$courseContent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi dengan slug "' . $slug . '" tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $courseContent->id,
                    'slug' => $courseContent->slug,
                    'judul' => $courseContent->judul,
                    'konten' => $courseContent->konten,
                    'urutan' => $courseContent->urutan,
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

            $navigation = CourseContent::where('course_description_id', $courseDescriptionId)
                ->select('id', 'slug', 'judul', 'urutan')
                ->orderBy('urutan', 'asc')
                ->orderBy('id', 'asc')
                ->get()
                ->map(function ($content) {
                    return [
                        'id' => $content->id,
                        'slug' => $content->slug,
                        'judul' => $content->judul,
                        'urutan' => $content->urutan
                    ];
                });

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
            $currentContent = CourseContent::where('slug', $slug)->first();

            if (!$currentContent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Materi tidak ditemukan'
                ], 404);
            }

            $prevContent = CourseContent::where('course_description_id', $currentContent->course_description_id)
                ->where('urutan', '<', $currentContent->urutan)
                ->orderBy('urutan', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $nextContent = CourseContent::where('course_description_id', $currentContent->course_description_id)
                ->where('urutan', '>', $currentContent->urutan)
                ->orderBy('urutan', 'asc')
                ->orderBy('id', 'asc')
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'current' => [
                        'id' => $currentContent->id,
                        'slug' => $currentContent->slug,
                        'judul' => $currentContent->judul,
                        'urutan' => $currentContent->urutan
                    ],
                    'prev' => $prevContent ? [
                        'id' => $prevContent->id,
                        'slug' => $prevContent->slug,
                        'judul' => $prevContent->judul,
                        'urutan' => $prevContent->urutan
                    ] : null,
                    'next' => $nextContent ? [
                        'id' => $nextContent->id,
                        'slug' => $nextContent->slug,
                        'judul' => $nextContent->judul,
                        'urutan' => $nextContent->urutan
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

    // Additional method for listing all course contents (for debugging)
    public function index(): JsonResponse
    {
        try {
            $courseContents = CourseContent::with('courseDescription')
                ->orderBy('course_description_id')
                ->orderBy('urutan')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $courseContents->map(function($content) {
                    return [
                        'id' => $content->id,
                        'slug' => $content->slug,
                        'judul' => $content->judul,
                        'urutan' => $content->urutan,
                        'course_description_id' => $content->course_description_id,
                        'course_title' => $content->courseDescription->title ?? 'Unknown Course'
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

    // Search functionality
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $courseId = $request->get('course_id');

            $searchQuery = CourseContent::query();

            if ($query) {
                $searchQuery->where(function($q) use ($query) {
                    $q->where('judul', 'LIKE', "%{$query}%")
                      ->orWhere('konten', 'LIKE', "%{$query}%");
                });
            }

            if ($courseId) {
                $searchQuery->where('course_description_id', $courseId);
            }

            $results = $searchQuery->orderBy('urutan', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $results->map(function($content) {
                    return [
                        'id' => $content->id,
                        'slug' => $content->slug,
                        'judul' => $content->judul,
                        'urutan' => $content->urutan,
                        'course_description_id' => $content->course_description_id,
                        'snippet' => substr(strip_tags($content->konten), 0, 150) . '...'
                    ];
                }),
                'total' => $results->count(),
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
