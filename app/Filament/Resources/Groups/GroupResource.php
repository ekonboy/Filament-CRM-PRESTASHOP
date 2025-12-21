<?php

namespace App\Filament\Resources\Groups;

use App\Filament\Resources\Groups\Pages\CreateGroup;
use App\Filament\Resources\Groups\Pages\EditGroup;
use App\Filament\Resources\Groups\Pages\ListGroups;
use App\Models\Group;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationLabel = 'Grupos';

    protected static ?string $slug = 'groups';

    protected static ?string $recordTitleAttribute = 'id_group';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('id_group')
                    ->label('ID')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($record) => filled($record)),

                \Filament\Forms\Components\TextInput::make('name_es')
                    ->label('Nombre (ES)')
                    ->required()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (\Filament\Forms\Components\TextInput $component, ?Group $record): void {
                        if (! $record) {
                            return;
                        }

                        $component->state($record->langEs?->name);
                    }),

                \Filament\Forms\Components\TextInput::make('reduction')
                    ->label('% descuento')
                    ->numeric()
                    ->required()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['langEs']))
            ->headerActions([
                CreateAction::make()
                    ->schema(fn (Schema $schema): ?Schema => static::form($schema))
                    ->slideOver(),
            ])
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('id_group')
                    ->label('ID')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('name_es')
                    ->label('Nombre (ES)')
                    ->state(fn (Group $record): string => $record->langEs?->name ?? (string) $record->id_group)
                    ->searchable(),

                \Filament\Tables\Columns\TextColumn::make('reduction')
                    ->label('% descuento')
                    ->numeric()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema(fn (Schema $schema): ?Schema => static::form($schema))
                    ->slideOver(),
            ])
            ->bulkActions([]);
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user-group';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGroups::route('/'),
            'create' => CreateGroup::route('/create'),
            'edit' => EditGroup::route('/{record}/edit'),
        ];
    }
}
