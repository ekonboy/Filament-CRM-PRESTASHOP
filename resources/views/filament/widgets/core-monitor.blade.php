<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-cpu-chip" icon-color="primary">
        <x-slot name="heading">Panel de Control de Integración (System Health)</x-slot>

        {{-- BARRA SUPERIOR: Status y Botón alineados --}}
        <div class="flex items-center justify-between pb-4 border-b border-gray-50 mb-4">
            <div class="flex items-center gap-3">
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $webservice_active ? 'bg-success-400' : 'bg-danger-400' }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ $webservice_active ? 'bg-success-500' : 'bg-danger-500' }}"></span>
                </div>
                <span class="text-xs font-bold uppercase tracking-tight">WebService: {{ $webservice_active ? 'ONLINE' : 'OFFLINE' }}</span>
            </div>

            <x-filament::button
                color="gray"
                size="xs"
                icon="heroicon-m-arrow-path"
                wire:click="testConnection"
            >
                <span wire:loading.remove>Test Connection</span>
                <span wire:loading>Connecting...</span>
            </x-filament::button>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <div class="fi-wi-stats-overview-stat !p-4">
                <div class="fi-wi-stats-overview-stat-content">
                    <div class="fi-wi-stats-overview-stat-label-ctn">
                        <x-filament::icon icon="heroicon-o-building-storefront" class="fi-icon" />
                        <span class="fi-wi-stats-overview-stat-label">Tienda</span>
                    </div>
                    <div class="fi-wi-stats-overview-stat-value !text-2xl">{{ $is_multishop ? 'Multitienda' : 'Individual' }}</div>
                    <div class="fi-wi-stats-overview-stat-description">
                        <span>Configuración activa</span>
                    </div>
                </div>
            </div>

            <div class="fi-wi-stats-overview-stat !p-4">
                <div class="fi-wi-stats-overview-stat-content">
                    <div class="fi-wi-stats-overview-stat-label-ctn">
                        <x-filament::icon icon="heroicon-o-map" class="fi-icon" />
                        <span class="fi-wi-stats-overview-stat-label">Idiomas</span>
                    </div>
                    <div class="fi-wi-stats-overview-stat-value !text-2xl">{{ $active_languages }}</div>
                    <div class="fi-wi-stats-overview-stat-description">
                        <span>Activos en PrestaShop</span>
                    </div>
                </div>
            </div>

            <div class="fi-wi-stats-overview-stat !p-4">
                <div class="fi-wi-stats-overview-stat-content">
                    <div class="fi-wi-stats-overview-stat-label-ctn">
                        <x-filament::icon icon="heroicon-o-truck" class="fi-icon" />
                        <span class="fi-wi-stats-overview-stat-label">Logística</span>
                    </div>
                    <div class="fi-wi-stats-overview-stat-value !text-2xl">{{ $active_carriers }}</div>
                    <div class="fi-wi-stats-overview-stat-description">
                        <span>Transportistas activos</span>
                    </div>
                </div>
            </div>

            <div class="fi-wi-stats-overview-stat !p-4">
                <div class="fi-wi-stats-overview-stat-content">
                    <div class="fi-wi-stats-overview-stat-label-ctn">
                        <x-filament::icon icon="heroicon-o-credit-card" class="fi-icon" />
                        <span class="fi-wi-stats-overview-stat-label">Pagos</span>
                    </div>
                    <div class="fi-wi-stats-overview-stat-value !text-2xl">{{ $active_payments }}</div>
                    <div class="fi-wi-stats-overview-stat-description">
                        <span>Módulos activos (heurística)</span>
                    </div>
                </div>
            </div>

            <div class="fi-wi-stats-overview-stat !p-4">
                <div class="fi-wi-stats-overview-stat-content">
                    <div class="fi-wi-stats-overview-stat-label-ctn">
                        <x-filament::icon icon="heroicon-o-clipboard-document-list" class="fi-icon" />
                        <span class="fi-wi-stats-overview-stat-label">Logins (Hoy)</span>
                    </div>
                    <div class="fi-wi-stats-overview-stat-value !text-2xl">{{ $today_logins }}</div>
                    <div class="fi-wi-stats-overview-stat-description">
                        <span>Auditoría del panel</span>
                    </div>
                </div>
            </div>

            <div class="fi-wi-stats-overview-stat !p-4">
                <div class="fi-wi-stats-overview-stat-content">
                    <div class="fi-wi-stats-overview-stat-label-ctn">
                        <x-filament::icon icon="heroicon-o-tag" class="fi-icon" />
                        <span class="fi-wi-stats-overview-stat-label">Productos</span>
                    </div>
                    <div class="fi-wi-stats-overview-stat-value !text-2xl">{{ $total_products }}</div>
                    <div class="fi-wi-stats-overview-stat-description">
                        <span>Total en catálogo</span>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
