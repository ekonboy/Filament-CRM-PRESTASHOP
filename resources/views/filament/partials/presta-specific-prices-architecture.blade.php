<div class="flex items-center gap-2">
    <x-filament::icon-button
        icon="heroicon-o-question-mark-circle"
        color="gray"
        size="sm"
        x-on:click="$dispatch('open-modal', { id: 'presta-specific-prices-architecture' })"
    />

    <x-filament::modal
        id="presta-specific-prices-architecture"
        slide-over
        width="2xl"
        :close-by-clicking-away="true"
    >
        <x-slot name="heading">
            Presta Specific Prices - Documentación
        </x-slot>

        @include('filament.architecture.presta-specific-prices')
    </x-filament::modal>
</div>
