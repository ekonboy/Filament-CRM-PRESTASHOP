<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getFormContainerWidth(): ?string
    {
        return 'screen'; // Todo el ancho de pantalla
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_post')
                ->label('Ver post')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->url(fn ($record) => $record?->url)
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
