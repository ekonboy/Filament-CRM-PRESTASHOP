<x-filament::modal
    id="system-architecture"
    slide-over
    width="2xl"
    :close-by-clicking-away="true"
>
    <x-slot name="heading">
        System Architecture
    </x-slot>

    @php($routeName = request()->route()?->getName())
    @php($views = (array) config('system_architecture.views', []))
    @php($view = $views[$routeName] ?? 'filament.architecture.default')

    <div class="space-y-4">
        <div class="text-sm text-gray-500">
            {{ $routeName ?? request()->path() }}
        </div>

        @includeIf($view)
    </div>
</x-filament::modal>
