<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\ManageArticles;
use App\Models\Article;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Artikel';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('journalist_id')
                    ->relationship('journalist', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('excerpt')
                    ->rows(3)
                    ->maxLength(200),
                RichEditor::make('content')
                    ->required(),
                FileUpload::make('featured_image')
                    ->image()
                    ->directory('articles/featured')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240),
                TextInput::make('video_url')
                    ->maxLength(255)
                    ->helperText('Gunakan URL YouTube atau Vimeo.')
                    ->rule(static function () {
                        return static function (string $attribute, mixed $value, \Closure $fail): void {
                            if (! $value) {
                                return;
                            }

                            $isYoutube = preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//i', (string) $value) === 1;
                            $isVimeo = preg_match('/^(https?:\/\/)?(www\.)?vimeo\.com\//i', (string) $value) === 1;

                            if (! $isYoutube && ! $isVimeo) {
                                $fail('URL video tidak valid. Gunakan URL dari YouTube atau Vimeo.');
                            }
                        };
                    }),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->live()
                    ->default('draft')
                    ->afterStateUpdated(function (mixed $state, Set $set): void {
                        if ($state === 'published') {
                            $set('published_at', now());
                        }
                    })
                    ->required(),
                DateTimePicker::make('published_at')
                    ->required(fn (callable $get): bool => $get('status') === 'published'),
                Toggle::make('is_headline')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                TextColumn::make('journalist.name')
                    ->label('Jurnalis'),
                TextColumn::make('status')
                    ->badge(),
                IconColumn::make('is_headline')
                    ->boolean(),
                TextColumn::make('view_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageArticles::route('/'),
        ];
    }
}
