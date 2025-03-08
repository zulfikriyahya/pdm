<?php

namespace App\Filament\Resources\TahunPelajaranResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TahunPelajaranResource;

class EditTahunPelajaran extends EditRecord
{
    protected static string $resource = TahunPelajaranResource::class;

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
