<?php

namespace App\Filament\Pages;

use App\Models\LoginLog;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class LoginLogs extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.login-logs';

    protected static ?string $title = 'Registro de Conexiones';

    protected static ?string $navigationLabel = 'Login Logs';

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LoginLog::query()->latest())
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user_id')
                    ->label('ID Usuario')
                    ->sortable(),
                TextColumn::make('user_name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('browser')
                    ->label('Navegador')
                    ->sortable(),
                TextColumn::make('platform')
                    ->label('Plataforma')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}
