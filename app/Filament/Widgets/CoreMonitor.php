<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class CoreMonitor extends BaseWidget
{
    // Orden de aparición (arriba del todo)
    protected static ?int $sort = -2;

    protected function getColumns(): int
    {
        return 6;
    }

    public function testConnection()
    {
        try {
            // Intentamos una consulta rápida a PrestaShop
            DB::table('soft_shop')->first();

            Notification::make()
                ->title('Conexión Exitosa')
                ->body('El puente con la base de datos de PrestaShop está activo.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error de Conexión')
                ->body('No se pudo comunicar con PrestaShop. Revisa el archivo .env')
                ->danger()
                ->send();
        }
    }

    protected function getStats(): array
    {
        $webserviceActive = DB::table('soft_webservice_account')->where('active', 1)->exists();
        $isMultishop = DB::table('soft_shop')->count() > 1;

        $activeLanguages = DB::table('soft_lang')->where('active', 1)->count();
        $activeCarriers = DB::table('soft_carrier')->where('active', 1)->where('deleted', 0)->count();
        $activePayments = DB::table('soft_module')->where('name', 'like', '%payment%')->where('active', 1)->count();

        $todayLogins = \App\Models\LoginLog::whereDate('created_at', today())->count();
        $totalProducts = DB::table('soft_product')->count();

        return [
            Stat::make('WebService', $webserviceActive ? 'ONLINE' : 'OFFLINE')
                ->description('Estado del puente PrestaShop')
                ->color($webserviceActive ? 'success' : 'danger')
                ->icon('heroicon-o-signal'),

            Stat::make('Tienda', $isMultishop ? 'Multitienda' : 'Individual')
                ->description('Configuración activa')
                ->color('gray')
                ->icon('heroicon-o-building-storefront'),

            Stat::make('Idiomas', $activeLanguages)
                ->description('Activos')
                ->color('primary')
                ->icon('heroicon-o-map'),

            Stat::make('Logística', $activeCarriers)
                ->description('Carriers activos')
                ->color('info')
                ->icon('heroicon-o-truck'),

            Stat::make('Pagos', $activePayments)
                ->description('Módulos payment*')
                ->color('warning')
                ->icon('heroicon-o-credit-card'),

            Stat::make('Logins (Hoy)', $todayLogins)
                ->description('Auditoría panel')
                ->color('gray')
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Productos', $totalProducts)
                ->description('Total catálogo')
                ->color('gray')
                ->icon('heroicon-o-tag'),

            Stat::make('Test Connection', 'Ejecutar')
                ->description('Comprueba conexión DB')
                ->descriptionColor('success')
                ->color('gray')
                ->icon('heroicon-m-arrow-path')
                ->extraAttributes([
                    'class' => 'cursor-pointer transition-all duration-300
                    border-l-4 border-primary-500
                    hover:shadow-lg hover:-translate-y-1
                    ring-1 ring-primary-500/50
                    bg-primary-50/5 dark:bg-primary-500/5',
                    'wire:click' => 'testConnection',
                    'wire:loading.class' => 'opacity-50 pointer-events-none',
                ]),
        ];
    }
}
