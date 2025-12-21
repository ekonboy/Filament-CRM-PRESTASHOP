<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TopSellingProducts extends ChartWidget
{
    protected static ?int $sort = 3;
    protected ?string $heading = 'Top Selling Products';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
