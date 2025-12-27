{{-- <x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6" style="margin-top: 8px;">
            <x-filament::button type="submit">
                Guardar cambios CSS
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page> --}}

<x-filament-panels::page>
    <form wire:submit="save">
        <style>
            /* Estilo "Editor" para el textarea nativo */
            .modern-textarea {
                font-family: 'Fira Code', 'Cascadia Code', 'Source Code Pro', monospace !important;
                font-size: 14px !important;
                line-height: 1.6 !important;
                background-color: #1e1e1e !important;
                color: #d4d4d4 !important;
                border: 1px solid #333 !important;
                border-radius: 8px !important;
                padding: 20px !important;
                tab-size: 4;
                outline: none !important;
                box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
                width: 100%;
                min-height: 600px;
            }
            .modern-textarea:focus {
                border-color: #4ade80 !important;
                box-shadow: 0 0 0 2px rgba(74, 222, 128, 0.2) !important;
            }
        </style>

        {{ $this->form }}

        <div style="margin-top: 16px;">
            <x-filament::button type="submit" size="xl">
                Guardar cambios CSS
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
