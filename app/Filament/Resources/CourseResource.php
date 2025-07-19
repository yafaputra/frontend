<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Perhatikan penggunaan 'description.nama_kolom' di semua field.
                // Ini memberitahu Filament untuk menyimpan data ke relasi 'description' (model CourseDescriptions)
                
                Forms\Components\Section::make('Course Information')
                    ->schema([
                        Forms\Components\TextInput::make('description.title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('description.tag')
                            ->label('Category / Tag')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description.overview')
                            ->label('Overview')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('description.image_url')
                            ->label('Cover Image')
                            ->image()
                            ->directory('course-covers'),
                        Forms\Components\FileUpload::make('description.thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('course-thumbnails'),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('description.price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('description.price_discount')
                            ->label('Discounted Price')
                            ->numeric()
                            ->prefix('Rp'),
                    ])->columns(2),

                Forms\Components\Section::make('Instructor')
                    ->schema([
                        Forms\Components\TextInput::make('description.instructor_name')
                            ->label('Instructor Name')
                            ->required(),
                        Forms\Components\TextInput::make('description.instructor_position')
                            ->label('Instructor Position')
                            ->required(),
                        Forms\Components\FileUpload::make('description.instructor_image_url')
                            ->label('Instructor Photo')
                            ->image()
                            ->directory('instructors'),
                    ])->columns(3),

                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('description.video_count')
                            ->label('Video Count')
                            ->numeric(),
                        Forms\Components\TextInput::make('description.duration')
                            ->label('Duration')
                            ->placeholder('e.g., 10h 30m'),
                        Forms\Components\Repeater::make('description.features')
                            ->label('Features')
                            ->schema([
                                Forms\Components\TextInput::make('feature_name')->required(),
                            ])
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Bagian tabel menampilkan data dari model Course (hasil sinkronisasi)
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Thumbnail'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('instructor'),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}