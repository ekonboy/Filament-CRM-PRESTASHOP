<div class="flex items-center gap-2">
    <x-filament::icon-button
        icon="heroicon-o-hand-thumb-up"
        color="gray"
        size="sm"
        x-on:click="$dispatch('open-modal', { id: 'system-architecture' })"
    />

    <x-filament::modal
        id="system-architecture"
        slide-over
        width="2xl"
        :close-by-clicking-away="true"
    >
        <x-slot name="heading">
            System Architecture
        </x-slot>

        <div class="space-y-4">
            <div class="text-sm text-gray-500">
                {{ request()->path() }}
            </div>
            <div class="text-sm text-gray-700">

            </div>
        </div>
    </x-filament::modal>
</div>
