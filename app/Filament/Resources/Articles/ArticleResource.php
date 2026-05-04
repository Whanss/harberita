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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    public static function getRecordRouteKeyName(): string
    {
        return 'id';
    }

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
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('journalist_id')
                    ->label('Jurnalis')
                    ->relationship('journalist', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                Textarea::make('excerpt')
                    ->label('Ringkasan')
                    ->rows(3)
                    ->maxLength(200),
                RichEditor::make('content')
                    ->label('Konten')
                    ->required(),
                FileUpload::make('featured_image')
                    ->label('Gambar Utama')
                    ->image()
                    ->disk('public')
                    ->directory('articles/featured')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(10240)
                    ->visibility('public'),
                TextInput::make('video_url')
                    ->label('URL Video')
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
                    ->label('Status')
                    ->options([
                        'draft'     => 'Draf',
                        'published' => 'Dipublikasikan',
                        'archived'  => 'Diarsipkan',
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
                    ->label('Tanggal Publikasi')
                    ->required(fn (callable $get): bool => $get('status') === 'published'),
                Toggle::make('is_headline')
                    ->label('Headline')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->size(40)
                    ->circular()
                    ->defaultImageUrl(null),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('medium')
                    ->limit(50),
                TextColumn::make('journalist.name')
                    ->label('Penulis')
                    ->searchable()
                    ->default('-'),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('orange'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft'     => 'Draf',
                        'published' => 'Dipublikasikan',
                        'archived'  => 'Diarsipkan',
                        default     => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        'archived'  => 'gray',
                        default     => 'gray',
                    }),
                IconColumn::make('is_headline')
                    ->label('Headline')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star'),
                TextColumn::make('view_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->alignment('center'),
                TextColumn::make('published_at')
                    ->label('Tanggal Publikasi')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
                DeleteAction::make()
                    ->icon('heroicon-o-trash'),
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
