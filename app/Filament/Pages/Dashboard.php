<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\SystemInfoWidget;


class Dashboard extends Page
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            SystemInfoWidget::class,
        ];
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-home';
    }






}
