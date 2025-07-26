<?php

namespace App\Filament\Resources\CourseContentResource\Pages;

use App\Filament\Resources\CourseContentResource;
use App\Models\CourseContent;
use App\Models\CourseDescriptions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class CreateCourseContent extends CreateRecord
{
    protected static string $resource = CourseContentResource::class;

    protected static ?string $title = 'Create Course Materials';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Extract materials from the form data
        $materials = $data['materials'] ?? [];
        $courseDescriptionId = $data['course_description_id'];
        $courseTitle = $data['course_title'];

        // Validate that we have materials
        if (empty($materials)) {
            throw new \Exception('At least one material is required');
        }

        $createdMaterials = [];

        // Create each material
        foreach ($materials as $materialData) {
            $material = CourseContent::create([
                'course_description_id' => $courseDescriptionId,
                'course_title' => $courseTitle,
                'judul' => $materialData['judul'],
                'slug' => $materialData['slug'],
                'konten' => $materialData['konten'],
                'urutan' => $materialData['urutan'] ?? 0,
            ]);

            $createdMaterials[] = $material;
        }

        // Send success notification
        Notification::make()
            ->title('Materials Created Successfully')
            ->body(count($createdMaterials) . ' materials have been created for this course.')
            ->success()
            ->send();

        // Return the first created material (required by Filament)
        return $createdMaterials[0];
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Course materials created successfully';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure materials have proper ordering if not set
        if (isset($data['materials'])) {
            foreach ($data['materials'] as $index => &$material) {
                if (empty($material['urutan'])) {
                    $material['urutan'] = $index + 1;
                }
            }
        }

        return $data;
    }
}

