<?php

namespace App\Filament\Resources\Journalists\Pages;

use App\Filament\Resources\Journalists\JournalistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJournalists extends ManageRecords
{
    protected static string $resource = JournalistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
