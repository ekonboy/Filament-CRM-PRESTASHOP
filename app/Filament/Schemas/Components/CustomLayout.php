<?php

namespace App\Filament\Schemas\Components;

use Filament\Schemas\Components\Component;

class CustomLayout extends Component
{
    protected string $view = 'filament.schemas.components.custom-layout';

    public static function make(): static
    {
        return app(static::class);
    }
}
