<?php

namespace App\Filament\Resources\CourseContentResource\Pages;

use App\Filament\Resources\CourseContentResource;
use App\Models\CourseContent;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCourseContent extends EditRecord
{
    protected static string $resource = CourseContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('add_more_materials')
                ->label('Add More Materials')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->url(fn (): string => CourseContentResource::getUrl('create', [
                    'course_id' => $this->record->course_description_id
                ])),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load all materials for this course to allow batch editing
        $courseId = $this->record->course_description_id;
        $allMaterials = CourseContent::where('course_description_id', $courseId)
            ->orderBy('urutan')
            ->get()
            ->map(function ($material) {
                return [
                    'id' => $material->id,
                    'judul' => $material->judul,
                    'slug' => $material->slug,
                    'konten' => $material->konten,
                    'urutan' => $material->urutan,
                ];
            })
            ->toArray();

        $data['materials'] = $allMaterials;

        return $data;
    }

    protected function handleRecordUpdate($record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $materials = $data['materials'] ?? [];
        $courseDescriptionId = $data['course_description_id'];
        $courseTitle = $data['course_title'];

        // Get existing materials for this course
        $existingMaterials = CourseContent::where('course_description_id', $courseDescriptionId)->get();
        $existingIds = $existingMaterials->pluck('id')->toArray();
        $updatedIds = [];

        // Update or create materials
        foreach ($materials as $materialData) {
            if (isset($materialData['id']) && in_array($materialData['id'], $existingIds)) {
                // Update existing material
                $material = CourseContent::find($materialData['id']);
                $material->update([
                    'course_description_id' => $courseDescriptionId,
                    'course_title' => $courseTitle,
                    'judul' => $materialData['judul'],
                    'slug' => $materialData['slug'],
                    'konten' => $materialData['konten'],
                    'urutan' => $materialData['urutan'] ?? 0,
                ]);
                $updatedIds[] = $material->id;
            } else {
                // Create new material
                $material = CourseContent::create([
                    'course_description_id' => $courseDescriptionId,
                    'course_title' => $courseTitle,
                    'judul' => $materialData['judul'],
                    'slug' => $materialData['slug'],
                    'konten' => $materialData['konten'],
                    'urutan' => $materialData['urutan'] ?? 0,
                ]);
                $updatedIds[] = $material->id;
            }
        }

        // Delete materials that are no longer in the form
        $materialsToDelete = array_diff($existingIds, $updatedIds);
        if (!empty($materialsToDelete)) {
            CourseContent::whereIn('id', $materialsToDelete)->delete();
        }

        Notification::make()
            ->title('Materials Updated Successfully')
            ->body(count($materials) . ' materials have been updated for this course.')
            ->success()
            ->send();

        return $record;
    }

    protected function getUpdatedNotificationTitle(): ?string
    {
        return 'Course materials updated successfully';
    }
}
