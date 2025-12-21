<?php

namespace App\Filament\Resources\EmailTemplateResource\Pages;

use App\Filament\Resources\EmailTemplateResource;
use App\Models\EmailTemplateLang;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditEmailTemplate extends EditRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('preview')
                ->label('Ver en navegador')
                ->color('gray')
                ->icon('heroicon-o-eye')
                ->url(function (self $livewire): string {
                    $record = $livewire->getRecord();
                    $langId = $livewire->data['language_id'] ?? 3;

                    return route('email-templates.preview', [
                        'template' => $record->id_email_template,
                        'lang' => $langId,
                    ]);
                })
                ->openUrlInNewTab(),

            \Filament\Actions\Action::make('send_test')
                ->label('Enviar Test')
                ->color('success')
                ->icon('heroicon-o-paper-airplane')
                ->form([
                    \Filament\Forms\Components\TextInput::make('test_email')
                        ->label('Email de prueba')
                        ->email()
                        ->required(),
                ])
                ->action(function (array $data, Model $record) {
                    $email = $data['test_email'];

                    $langId = $data['language_id'] ?? 3;
                    $lang = $record->langs->firstWhere('id_lang', $langId);
                    
                    $subject = $lang->subject ?? 'No Subject';
                    $content = $lang->html_content ?? 'No Content';

                    try {
                        \Illuminate\Support\Facades\Mail::raw('Test Content', function ($message) use ($email, $subject, $content) {
                            $message->to($email)
                                ->subject($subject)
                                ->html($content); 
                        });
                        \Filament\Notifications\Notification::make()->title('Email enviado')->success()->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()->title('Error')->body($e->getMessage())->danger()->send();
                    }
                }),
            \Filament\Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        $translations = $record->langs->keyBy('id_lang')->map(fn($item) => [
            'subject' => $item->subject,
            'html_content' => $item->html_content,
        ])->toArray();

        $data['translations'] = $translations;
        
        // Load default language (3) into main fields
        $defaultLang = 3;
        $data['language_id'] = $defaultLang;
        $data['subject'] = $translations[$defaultLang]['subject'] ?? '';
        $data['html_content'] = $translations[$defaultLang]['html_content'] ?? '';

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'name' => $data['name'],
            'active' => $data['active'] ?? true,
        ]);

        if (isset($data['translations']) && is_array($data['translations'])) {
            foreach ($data['translations'] as $langId => $trans) {
                EmailTemplateLang::updateOrCreate(
                    [
                        'id_template' => $record->id_email_template,
                        'id_lang' => $langId,
                    ],
                    [
                        'subject' => $trans['subject'] ?? '',
                        'html_content' => $trans['html_content'] ?? '',
                    ]
                );
            }
        }

        return $record;
    }
}
