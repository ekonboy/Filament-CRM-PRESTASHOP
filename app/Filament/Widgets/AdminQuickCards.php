<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AdminQuickCards extends Widget
{
    protected string $view = 'filament.widgets.admin-quick-cards';

    protected int | string | array $columnSpan = 'full';

    public function getIpAddress(): string
    {
        return request()->ip();
    }

    public function getFilamentVersion(): string
    {
        return 'v4.3.1';
    }
}
