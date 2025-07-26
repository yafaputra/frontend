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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Card;
use Filament\Forms\Get;
use Illuminate\Support\Str;

class CourseContentResource extends Resource
{
    protected static ?string $model = CourseContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Courses';
    protected static ?string $navigationLabel = 'Course Materials';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Course Information')
                    ->description('Select the course and add multiple materials at once')
                    ->schema([
                        Forms\Components\Select::make('course_description_id')
                            ->label('Course Description')
                            ->options(CourseDescriptions::pluck('title', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $course = CourseDescriptions::find($state);
                                    if ($course) {
                                        $set('course_title', $course->title);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('course_title')
                            ->label('Course Title')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Section::make('Course Materials')
                    ->description('Add multiple materials for this course')
                    ->schema([
                        Repeater::make('materials')
                            ->label('Materials')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                       Forms\Components\TextInput::make('judul')
                                            ->label('Material Title')
                                            ->required()
                                            ->live(debounce: 500) // Beri jeda 500ms sebelum update
                                            ->afterStateUpdated(function ($state, Forms\Set $set, Get $get) {
                                                if ($state && !$get('slug')) { // Hanya generate slug jika slug kosong
                                                    $slug = Str::slug($state);
                                                    $courseTitle = $get('../../course_title');
                                                    if ($courseTitle) {
                                                        $slug = Str::slug($courseTitle) . '-' . $slug;
                                                    }
                                                    $set('slug', $slug);
                                                }
                                            }),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('Slug')
                                            ->required()
                                            ->unique(CourseContent::class, 'slug', ignoreRecord: true)
                                            ->helperText('Auto-generated from title, you can modify if needed'),
                                    ]),

                                Forms\Components\RichEditor::make('konten')
                                    ->label('Content')
                                    ->required()
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ]),

                                Forms\Components\TextInput::make('urutan')
                                    ->label('Order')
                                    ->numeric()
                                    ->default(function (Get $get) {
                                        // Auto increment order based on existing materials
                                        $materials = $get('../../materials') ?? [];
                                        return count($materials) + 1;
                                    })
                                    ->helperText('Material order in the course'),
                            ])
                            ->collapsed()
                            ->cloneable()
                            ->collapsible()
                            ->deleteAction(
                                fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                            )
                            ->itemLabel(fn (array $state): ?string => $state['judul'] ?? 'New Material')
                            ->addActionLabel('Add New Material')
                            ->minItems(1)
                            ->maxItems(50)
                            ->grid(1)
                    ])
            ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('courseDescription.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Material Title')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_description_id')
                    ->label('Course')
                    ->options(CourseDescriptions::pluck('title', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('course_description_id', 'asc')
            ->defaultSort('urutan', 'asc');
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
            'index' => Pages\ListCourseContents::route('/'),
            'create' => Pages\CreateCourseContent::route('/create'),
            'edit' => Pages\EditCourseContent::route('/{record}/edit'),
            'view' => Pages\ViewCourseContent::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }
}
