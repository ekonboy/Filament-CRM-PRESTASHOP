<?php

namespace App\Filament\Resources\EmailTemplateResource\Pages;

use App\Filament\Resources\EmailTemplateResource;
use App\Models\EmailTemplateLang;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEmailTemplate extends CreateRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create([
            'name' => $data['name'],
            'active' => $data['active'] ?? true,
        ]);

        if (isset($data['translations']) && is_array($data['translations'])) {
            foreach ($data['translations'] as $langId => $trans) {
                EmailTemplateLang::create([
                    'id_template' => $record->id_email_template, 
                    'id_lang' => $langId,
                    'subject' => $trans['subject'] ?? '',
                    'html_content' => $trans['html_content'] ?? '',
                ]);
            }
        }
        
        // Also save the current form state if not in translations array
        // (Though our logic tries to sync everything to 'translations')

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return url('/admin/email-templates');
    }
}
