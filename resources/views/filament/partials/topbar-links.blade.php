<div class="flex items-center gap-2">
    <x-filament::button
        tag="a"
        href="{{ env('SHOP_URL', 'https://sabi.vistarapida.es') }}/en/"
        target="_blank"
        rel="noopener noreferrer"
        color="success"
        class="fi-color-success"
        size="sm"
    >
        Visitar web
    </x-filament::button>

    <x-filament::button
        tag="a"
        href="{{ env('SHOP_URL', 'https://sabi.vistarapida.es') }}/risvtwqj/"
        target="_blank"
        rel="noopener noreferrer"
        color="danger"
        class="fi-color-danger"
        size="sm"
    >
        Backoffice
    </x-filament::button>
</div>
