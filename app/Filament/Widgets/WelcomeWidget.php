<?php

namespace App\Filament\Widgets;

use Filament\Widgets\AccountWidget;

class WelcomeWidget extends AccountWidget
{
    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.welcome-widget';

    protected int | string | array $columnSpan = 'full';

    public function getIpAddress(): string
    {
        return request()->ip();
    }
}
