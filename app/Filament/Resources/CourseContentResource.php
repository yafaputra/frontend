<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseContentResource\Pages;
use App\Models\CourseContent;
use App\Models\CourseDescriptions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;

class CourseContentResource extends Resource
{
    protected static ?string $model = CourseContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Courses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_description_id')
                    ->label('Course Description')
                    ->options(CourseDescriptions::pluck('title', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('course_title')->required(),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('judul')->required(),
                Forms\Components\Textarea::make('konten')
                    ->rows(6)
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('urutan')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course_title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('urutan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('courseDescription.title')
                    ->label('Course Description')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan relasi di sini jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseContents::route('/'),
            'create' => Pages\CreateCourseContent::route('/create'),
            'edit' => Pages\EditCourseContent::route('/{record}/edit'),
        ];
    }
}
