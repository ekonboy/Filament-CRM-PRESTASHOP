<div>
    <div class="inline-flex items-center" style="margin-left:20px">
        <x-filament::icon-button
            icon="heroicon-o-hand-thumb-up"
            color="gray"
            size="lg"
            tag="button"
            x-on:click.stop.prevent="$dispatch('open-modal', { id: 'presta-specific-prices-architecture' })"
        />
    </div>

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
