<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminQuickOverview extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected static ?int $sort = -10;

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $user = filament()->auth()->user();

        return [
            Stat::make('Usuario', filament()->getUserName($user))
                ->description('Acceso al panel')
                ->icon('heroicon-o-user'),

            Stat::make('IP', request()->ip())
                ->description('IP actual')
                ->icon('heroicon-o-globe-alt'),

            Stat::make('Conexiones', 'Ver log')
                ->description('Registro de accesos')
                ->icon('heroicon-o-clipboard-document-list')
                ->url(route('filament.admin.pages.login-logs')),

            Stat::make('Filament', 'v4.3.1')
                ->description('Panel UI')
                ->icon('heroicon-o-code-bracket'),
        ];
    }
}
