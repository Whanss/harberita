<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestArticlesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected static ?string $heading = 'Artikel Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Article::query()
                    ->with(['category', 'journalist'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->size(40)
                    ->circular()
                    ->defaultImageUrl(null),
                TextColumn::make('title')
                    ->label('Judul')
                    ->limit(50)
                    ->searchable()
                    ->weight('medium'),
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
                        'published' => 'Dipublikasikan',
                        'draft'     => 'Draf',
                        'archived'  => 'Diarsipkan',
                        default     => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        'archived'  => 'gray',
                        default     => 'gray',
                    }),
                TextColumn::make('view_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->alignment('center'),
                TextColumn::make('published_at')
                    ->label('Dipublikasikan')
                    ->since()
                    ->sortable(),
            ])
            ->paginated(false)
            ->defaultSort('published_at', 'desc');
    }
}
