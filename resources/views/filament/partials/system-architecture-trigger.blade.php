<div class="inline-flex items-center" style="margin-left:20px">
    <x-filament::icon-button
        icon="heroicon-o-hand-thumb-up"
        color="gray"
        size="lg"
        tag="button"
        x-on:click.stop.prevent="$dispatch('open-modal', { id: 'system-architecture' })"
    />
</div>
