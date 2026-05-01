<?php

namespace App\Filament\Resources\Comments;

use App\Filament\Resources\Comments\Pages\ManageComments;
use App\Models\Comment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Komentar';

    protected static ?string $recordTitleAttribute = 'content';

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('article_title')
                    ->label('Artikel')
                    ->content(fn (Comment $record): string => $record->article?->title ?? '-'),

                Placeholder::make('reader_name')
                    ->label('Pembaca')
                    ->content(fn (Comment $record): string => $record->reader?->name ?? '-'),

                Textarea::make('content')
                    ->label('Isi Komentar')
                    ->rows(4)
                    ->disabled(),

                Placeholder::make('created_at')
                    ->label('Dikirim pada')
                    ->content(fn (Comment $record): string => $record->created_at?->format('d M Y, H:i') ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                TextColumn::make('content')
                    ->label('Komentar')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('article.title')
                    ->label('Artikel')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('reader.name')
                    ->label('Pembaca')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'pending'  => 'Menunggu',
                        'rejected' => 'Ditolak',
                        default    => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending'  => 'warning',
                        'rejected' => 'danger',
                        default    => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->since()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make(),
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
            'index' => ManageComments::route('/'),
        ];
    }
}
