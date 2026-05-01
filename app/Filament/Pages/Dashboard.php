<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dasbor';

    protected static ?string $navigationLabel = 'Dasbor';

    protected static ?int $navigationSort = -2;

    public static function getNavigationGroup(): ?string
    {
        return null;
    }
}
