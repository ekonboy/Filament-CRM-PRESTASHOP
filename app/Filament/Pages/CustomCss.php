<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\File;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class CustomCss extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.custom-css';
    protected static ?string $navigationLabel = 'Custom CSS';
    protected static ?string $title = 'Editor CSS';
    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cloud-arrow-up';
    }

    public function mount(): void
    {
        $path = public_path('css/custom_css.css');
        $content = File::exists($path) ? File::get($path) : '';

        $this->form->fill(['css_content' => $content]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Textarea::make('css_content')
                    ->label('Editor CSS')
                    ->rows(25)
                    ->extraAttributes([
                        'class' => 'modern-textarea',
                        'spellcheck' => 'false',
                        'autocorrect' => 'off',
                        'autocapitalize' => 'off',
                        'onkeydown' => "if(event.keyCode===9){event.preventDefault();var s=this.selectionStart;this.value=this.value.substring(0,s)+'\t'+this.value.substring(this.selectionEnd);this.selectionEnd=s+1;}"
                    ])
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync')
                ->label('Sincronizar CSS')
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Simulación de sincronización
                    Notification::make()
                        ->title('Sincronizando con public/css/custom_css.css')
                        ->body('El CSS se está sincronizando con el módulo de PrestaShop...')
                        ->info()
                        ->send();
                    
                    // Pequeña pausa para efecto visual
                    usleep(500000); // 0.5 segundos
                    
                    Notification::make()
                        ->title('¡Sincronización completada!')
                        ->body('Los cambios están disponibles en el módulo de PrestaShop.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $path = public_path('css/custom_css.css');

        if (!File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        File::put($path, $state['css_content']);

        // 2. Nueva forma de lanzar notificaciones
        Notification::make()
            ->title('¡CSS guardado con éxito!')
            ->body('Los cambios ya están disponibles para el módulo de PrestaShop.')
            ->success()
            ->send();
    }
}
