<?php

namespace App\Filament\Resources\TahunPelajaranResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\TahunPelajaranResource;

class ViewTahunPelajaran extends ViewRecord
{
    protected static string $resource = TahunPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }
}
