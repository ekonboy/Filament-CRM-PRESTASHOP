<?php

namespace App\Filament\Resources\OrderTrackings;

use App\Filament\Resources\OrderTrackings\Pages\ListOrderTrackings;
use App\Models\EmailTemplate;
use App\Models\Language;
use App\Models\Order;
use App\Models\OrderTracking;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;

class OrderTrackingResource extends Resource
{
    protected static ?string $model = Order::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-truck';
    }

    protected static ?string $navigationLabel = 'Order Trackings';

    protected static ?string $slug = 'order-trackings';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('id_order', 'desc'))
            ->columns([
                TextColumn::make('id_order')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('reference')
                    ->label('Referencia')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-document')
                    ->iconColor('primary'),

                TextColumn::make('deliveryAddress.country')
                    ->label('Entrega')
                    ->state(fn (Order $record): string => $record->deliveryAddress?->country?->langEs?->name
                        ?: ($record->country_delivery ?? ''))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('deliveryAddress', function (Builder $addressQuery) use ($search): void {
                                $addressQuery->whereHas('country.langEs', function (Builder $countryLangQuery) use ($search): void {
                                    $countryLangQuery->where('name', 'like', "%{$search}%");
                                });
                            })
                            ->orWhere('country_delivery', 'like', "%{$search}%");
                    }),

                TextColumn::make('customer_fullname')
                    ->label('Cliente')
                    ->state(fn (Order $record): string => trim(
                        ($record->customer?->firstname ?? $record->firstname ?? '')
                        . ' ' .
                        ($record->customer?->lastname ?? $record->lastname ?? '')
                    ))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('customer', function (Builder $customerQuery) use ($search): void {
                                $customerQuery
                                    ->where('firstname', 'like', "%{$search}%")
                                    ->orWhere('lastname', 'like', "%{$search}%");
                            })
                            ->orWhere('firstname', 'like', "%{$search}%")
                            ->orWhere('lastname', 'like', "%{$search}%");
                    }),

                TextColumn::make('state.name')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Canceled' => 'danger', // Priorizar este caso
                        'Enviado', 'Pago aceptado', 'Entregado', 'Payment accepted', 'Delivered', 'Shipped' => 'success',
                        'Cancelado', 'canceled', 'Cancelled', 'CANCELED' => 'danger',
                        'Error de pago', 'Payment error' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('date_add')
                    ->label('Fecha')
                    ->dateTime(),

                TextColumn::make('tracking_code')
                    ->label('Tracking')
                    ->state(fn (Order $record): ?string => OrderTracking::query()
                        ->where('id_order', $record->id_order)
                        ->value('tracking_code'))
                    ->copyable(),
            ])
            ->recordActions([
                Action::make('send_tracking')
                    ->label('Enviar Tracking')
                    ->icon('heroicon-o-paper-airplane')
                    ->form([
                        Select::make('template_id')
                            ->label('Plantilla de email')
                            ->options(fn () => EmailTemplate::query()
                                ->where('active', true)
                                ->orderBy('id_email_template')
                                ->pluck('name', 'id_email_template')
                                ->all())
                            ->searchable()
                            ->required(),

                        Select::make('lang_id')
                            ->label('Idioma')
                            ->options(fn () => Language::query()
                                ->where('active', true)
                                ->orderBy('id_lang')
                                ->pluck('name', 'id_lang')
                                ->all())
                            ->default(3)
                            ->searchable()
                            ->required(),

                        TextInput::make('tracking_code')
                            ->label('Código de Seguimiento')
                            ->required()
                            ->default(fn (Order $record): ?string => OrderTracking::query()
                                ->where('id_order', $record->id_order)
                                ->value('tracking_code')),
                    ])
                    ->action(function (Order $record, array $data) {
                        OrderTracking::query()->updateOrCreate(
                            ['id_order' => $record->id_order],
                            [
                                'reference' => (string) ($record->reference ?? ''),
                                'customer_name' => trim(
                                    ($record->customer?->firstname ?? $record->firstname ?? '')
                                    . ' ' .
                                    ($record->customer?->lastname ?? $record->lastname ?? '')
                                ),
                                'country' => (string) ($record->country_delivery ?? ''),
                                'tracking_code' => (string) ($data['tracking_code'] ?? ''),
                                'status' => 'enviado',
                                'sent_at' => now(),
                            ],
                        );

                        // Enviar email con plantilla seleccionada
                        $email = $record->customer->email ?? $record->email;
                        if ($email) {
                            try {
                                $template = EmailTemplate::query()->findOrFail($data['template_id']);
                                $translation = $template->langs()->where('id_lang', $data['lang_id'])->first();

                                $subject = $translation?->subject ?: 'Información de seguimiento';
                                $html = $translation?->html_content ?: "Su pedido {$record->reference} ha sido enviado. Tracking: {$data['tracking_code']}";

                                $replacements = [
                                    '{reference}' => (string) ($record->reference ?? ''),
                                    '{tracking_code}' => (string) ($data['tracking_code'] ?? ''),
                                    '{order_id}' => (string) ($record->id_order ?? ''),
                                ];

                                $subject = strtr($subject, $replacements);
                                $html = strtr($html, $replacements);

                                Mail::html($html, function ($message) use ($email, $subject) {
                                    $message->to($email)->subject($subject);
                                });

                                Notification::make()
                                    ->title('Tracking enviado correctamente')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Error al enviar email')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        } else {
                            Notification::make()
                                ->title('Cliente sin email')
                                ->warning()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrderTrackings::route('/'),
        ];
    }
}
