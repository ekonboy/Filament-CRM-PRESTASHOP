<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(['lg' => 1])
                    ->schema([
                        Section::make('Información del Cliente')
                            ->compact()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('customer_name')
                                            ->label('Nombre del Cliente')
                                            ->disabled()
                                            ->formatStateUsing(function (?Order $record): string {
                                                if (!$record) return '';
                                                
                                                $firstname = $record->customer?->firstname ?? $record->firstname ?? '';
                                                $lastname = $record->customer?->lastname ?? $record->lastname ?? '';
                                                
                                                return trim($firstname . ' ' . $lastname);
                                            }),
                                        TextInput::make('customer_email')
                                            ->label('Email del Cliente')
                                            ->disabled()
                                            ->formatStateUsing(function (?Order $record): string {
                                                return $record->customer?->email ?? $record->email ?? '';
                                            }),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
