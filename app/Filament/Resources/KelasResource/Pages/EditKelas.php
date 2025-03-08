<?php

namespace App\Filament\Resources\KelasResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\KelasResource;

class EditKelas extends EditRecord
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
            // Actions\ForceDeleteAction::make(),
            // Actions\RestoreAction::make(),
        ];
    }
}
