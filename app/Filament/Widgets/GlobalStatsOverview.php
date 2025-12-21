<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GlobalStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        // Common variables
        $now = Carbon::now();
        $todayStr = $now->toDateString();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $validStates = [2, 3, 4]; // Pagado, Enviado, Entregado (aprox)

        // 1. Daily Sales
        $salesToday = DB::table('soft_orders')
            ->whereDate('date_add', $todayStr)
            ->whereIn('current_state', $validStates)
            ->sum('total_paid_tax_incl');

        // 2. Monthly Orders
        $ordersMonth = DB::table('soft_orders')
            ->whereBetween('date_add', [$monthStart, $monthEnd])
            ->whereIn('current_state', $validStates)
            ->count();

        // 3. Monthly Sales
        $salesMonth = DB::table('soft_orders')
            ->whereBetween('date_add', [$monthStart, $monthEnd])
            ->whereIn('current_state', $validStates)
            ->sum('total_paid_tax_incl');

        // 4. New Customers
        $customersMonth = DB::table('soft_customer')
            ->whereBetween('date_add', [$monthStart, $monthEnd])
            ->count();

        return [
            Stat::make('Ventas del día', number_format($salesToday, 2, ',', '.') . ' €')
                ->description('Ingresos generados hoy')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Pedidos del mes', $ordersMonth)
                ->description('Pedidos confirmados este mes')
                ->color('success')
                ->icon('heroicon-o-shopping-cart'),

            Stat::make('Ventas del mes', number_format($salesMonth, 2, ',', '.') . ' €')
                ->description('Ingresos generados este mes')
                ->color('info')
                ->icon('heroicon-o-calendar'),

            Stat::make('Nuevos clientes', $customersMonth)
                ->description('Altas registradas este mes')
                ->color('primary')
                ->icon('heroicon-o-users'),
        ];
    }
}
