<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailTemplateResource\Pages;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-duplicate';
    }
    
    public static function getNavigationGroup(): ?string
    {
        return 'Templates';
    }

    public static function getNavigationLabel(): string
    {
        return 'Email Templates';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_email_template')
                    ->label('ID')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($record) => filled($record)),

                TextInput::make('name')
                    ->label('Nombre Interno')
                    ->required(),
                
                Forms\Components\Toggle::make('active')
                    ->default(true),

                // Hidden field to store all translations state
                Hidden::make('translations')
                    ->default(function ($record) {
                        if (!$record) return [];
                        return $record->langs->keyBy('id_lang')->map(fn($item) => [
                            'subject' => $item->subject,
                            'html_content' => $item->html_content,
                        ])->toArray();
                    })
                    ->dehydrated(true), 

                Select::make('language_id')
                    ->label('Idioma')
                    ->options([
                        1 => 'English',
                        2 => 'Français',
                        3 => 'Español',
                    ])
                    ->default(3)
                    ->selectablePlaceholder(false)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Load data for selected language from 'translations' state
                        $translations = $get('translations') ?? [];
                        $data = $translations[$state] ?? ['subject' => '', 'html_content' => ''];
                        
                        $set('subject', $data['subject']);
                        $set('html_content', $data['html_content']);
                    }),

                TextInput::make('subject')
                    ->label('Asunto')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $get, $set) {
                        // Sync back to translations
                        $lang = $get('language_id');
                        $translations = $get('translations') ?? [];
                        $translations[$lang]['subject'] = $state;
                        $set('translations', $translations);
                    }),

                Textarea::make('html_content')
                    ->label('Contenido HTML')
                    ->rows(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $get, $set) {
                        // Sync back to translations
                        $lang = $get('language_id');
                        $translations = $get('translations') ?? [];
                        $translations[$lang]['html_content'] = $state;
                        $set('translations', $translations);
                    }),

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_email_template')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailTemplates::route('/'),
            'create' => Pages\CreateEmailTemplate::route('/create'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}
