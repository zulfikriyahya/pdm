<?php

namespace App\Filament\Resources\TahunPelajaranResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TahunPelajaranResource;

class ListTahunPelajarans extends ListRecords
{
    protected static string $resource = TahunPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(Auth::user()->is_admin === 'Administrator'),
        ];
    }
}
