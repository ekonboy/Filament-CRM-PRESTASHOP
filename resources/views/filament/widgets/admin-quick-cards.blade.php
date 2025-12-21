@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget>
    <div style="overflow-x: auto;">
        <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; min-width: 900px;">
        <x-filament::section>
            <div class="flex items-center gap-3">
                <x-filament-panels::avatar.user
                    size="lg"
                    :user="$user"
                    loading="lazy"
                />

                <div class="min-w-0">

                    <div class="truncate text-base font-semibold text-gray-950 dark:text-white">
                        {{ filament()->getUserName($user) }}
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="space-y-1">
                <div class="text-xs text-gray-500 dark:text-gray-400">IP actual</div>
                <div class="font-mono text-base font-semibold text-primary-600 dark:text-primary-400">
                    {{ $this->getIpAddress() }}
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="flex h-full flex-col justify-between gap-3">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Conexiones</div>

                </div>

                <div style="margin-top: 4px">
                    <x-filament::button
                        href="{{ route('filament.admin.pages.login-logs') }}"
                        tag="a"
                        color="gray"
                        icon="heroicon-o-clipboard-document-list"
                        size="sm"
                    >
                      Ver log
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="flex h-full flex-col justify-between gap-3">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400" style="margin-top: 4px">Filament {{ $this->getFilamentVersion() }}</div>

                </div>

                <form action="{{ filament()->getLogoutUrl() }}" method="post">
                    @csrf
                    <x-filament::button
                        color="gray"
                        icon="heroicon-o-arrow-left-end-on-rectangle"
                        tag="button"
                        type="submit"
                        size="sm"
                    >
                        Salir
                    </x-filament::button>
                </form>
            </div>
        </x-filament::section>
        </div>
    </div>
</x-filament-widgets::widget>
