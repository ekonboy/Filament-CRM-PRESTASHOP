@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-3 text-start">

            <div class="shrink-0">
                <x-filament-panels::avatar.user
                    size="lg"
                    :user="$user"
                    loading="lazy"
                />
            </div>

            <div class="min-w-0 flex-1">
                <h2 class="text-lg font-semibold text-gray-950 dark:text-white" style="line-height: 1.2;">
                    {{ filament()->getUserName($user) }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
                </p>
            </div>

            <div class="shrink-0">
                <div class="px-3 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg border">
                    <div class="text-gray-500 dark:text-gray-400" style="font-size: 0.7rem; text-transform: uppercase;">Tu IP</div>
                    <span class="font-mono font-bold text-primary-600 dark:text-primary-400">{{ $this->getIpAddress() }}</span>
                </div>
            </div>

            <div class="shrink-0 flex items-center gap-2 flex-wrap">
                <x-filament::button
                    href="{{ route('filament.admin.pages.login-logs') }}"
                    tag="a"
                    color="gray"
                    icon="heroicon-o-clipboard-document-list"
                    size="sm"
                    class="mt-4"
                >
                    Log
                </x-filament::button>

                <form action="{{ filament()->getLogoutUrl() }}" method="post" class="inline">
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

                <x-filament::button
                    color="gray"
                    icon="heroicon-o-code-bracket"
                    size="sm"
                    disabled
                >
                    v4.3.1
                </x-filament::button>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
