<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SystemInfoWidget extends Widget
{
    protected string $view = 'filament.widgets.system-info-widget';

    protected int | string | array $columnSpan = 'full';

    public function getDatabaseInfo(): array
    {
        return [
            'database' => Config::get('database.connections.mysql.database'),
            'prefix' => Config::get('database.connections.mysql.prefix', ''),
        ];
    }

    public function getPhpVersion(): string
    {
        return PHP_VERSION;
    }

    public function getAppDebug(): string
    {
        return Config::get('app.debug') ? 'true' : 'false';
    }

    public function getPrestashopVersion(): string
    {
        try {
            // Try to get version from config or database
            $version = Config::get('prestashop.version', 'Unknown');
            
            if ($version === 'Unknown') {
                // Try to get from database if prestashop_config table exists
                try {
                    $result = DB::table('ps_configuration')
                        ->where('name', 'PS_SHOP_VERSION')
                        ->value('value');
                    if ($result) {
                        $version = $result;
                    }
                } catch (\Exception $e) {
                    // Table doesn't exist or other DB error
                }
            }
            
            return $version;
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}
