<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\Group;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_shop_group')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('id_shop')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('id_gender')
                    ->required()
                    ->numeric(),
                Select::make('id_default_group')
                    ->label('Grupo')
                    ->options(fn () => Group::query()
                        ->with(['langEs'])
                        ->orderBy('id_group')
                        ->get()
                        ->mapWithKeys(fn (Group $group) => [$group->id_group => ($group->langEs?->name ?? (string) $group->id_group)])
                        ->all())
                    ->searchable()
                    ->required()
                    ->default(1),

                Select::make('group_ids')
                    ->label('Grupos')
                    ->options(fn () => Group::query()
                        ->with(['langEs'])
                        ->orderBy('id_group')
                        ->get()
                        ->mapWithKeys(fn (Group $group) => [$group->id_group => ($group->langEs?->name ?? (string) $group->id_group)])
                        ->all())
                    ->multiple()
                    ->searchable()
                    ->default(fn (?Customer $record): array => $record
                        ? $record->groups()->pluck('id_group')->all()
                        : []),
                TextInput::make('id_lang')
                    ->numeric(),
                TextInput::make('id_risk')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('company')
                    ->label('Empresa'),
                TextInput::make('siret')
                    ->label('NIF/VAT'),
                TextInput::make('ape'),
                TextInput::make('firstname')
                    ->required(),
                TextInput::make('lastname')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('passwd')
                    ->required(),
                DateTimePicker::make('last_passwd_gen')
                    ->required(),
                DatePicker::make('birthday')
                    ->nullable(),
                TextInput::make('newsletter')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('ip_registration_newsletter'),
                DateTimePicker::make('newsletter_date_add')
                    ->nullable(),
                TextInput::make('optin')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('website')
                    ->url(),
                TextInput::make('outstanding_allow_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('show_public_prices')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_payment_days')
                    ->required()
                    ->numeric()
                    ->default(60),
                TextInput::make('secure_key')
                    ->required()
                    ->default('-1'),
                Textarea::make('note')
                    ->columnSpanFull(),
                TextInput::make('active')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_guest')
                    ->required(),
                Toggle::make('deleted')
                    ->required(),
                DateTimePicker::make('date_add')
                    ->nullable()
                    ->required(),
                DateTimePicker::make('date_upd')
                    ->nullable()
                    ->required(),
                DateTimePicker::make('reset_password_validity')
                    ->nullable(),
            ]);
    }
}
