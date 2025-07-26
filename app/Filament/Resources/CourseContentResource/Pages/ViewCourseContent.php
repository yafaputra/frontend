<?php

namespace App\Filament\Resources\CourseContentResource\Pages;

use App\Filament\Resources\CourseContentResource;
use App\Models\CourseContent;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Support\Enums\FontWeight;

class ViewCourseContent extends ViewRecord
{
    protected static string $resource = CourseContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Course Information')
                    ->schema([
                        TextEntry::make('courseDescription.title')
                            ->label('Course Title')
                            ->weight(FontWeight::Bold),

                        TextEntry::make('course_title')
                            ->label('Internal Course Title'),

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make('Material Details')
                    ->schema([
                        TextEntry::make('judul')
                            ->label('Material Title')
                            ->weight(FontWeight::Bold),

                        TextEntry::make('slug')
                            ->label('Slug')
                            ->copyable(),

                        TextEntry::make('urutan')
                            ->label('Order')
                            ->badge(),

                        TextEntry::make('konten')
                            ->label('Content')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('All Course Materials')
                    ->schema([
                        TextEntry::make('all_materials')
                            ->label('')
                            ->getStateUsing(function ($record) {
                                $materials = CourseContent::where('course_description_id', $record->course_description_id)
                                    ->orderBy('urutan')
                                    ->get();

                                $list = '<div class="space-y-2">';
                                foreach ($materials as $material) {
                                    $current = $material->id === $record->id ? 'font-bold text-primary-600' : '';
                                    $list .= "<div class='p-3 border rounded {$current}'>";
                                    $list .= "<div class='flex justify-between items-center'>";
                                    $list .= "<span class='font-medium'>{$material->urutan}. {$material->judul}</span>";
                                    if ($material->id === $record->id) {
                                        $list .= "<span class='text-xs bg-primary-100 text-primary-700 px-2 py-1 rounded'>Current</span>";
                                    }
                                    $list .= "</div>";
                                    $list .= "<div class='text-sm text-gray-600 mt-1'>Slug: {$material->slug}</div>";
                                    $list .= "</div>";
                                }
                                $list .= '</div>';

                                return $list;
                            })
                            ->html(),
                    ]),
            ]);
    }
}
