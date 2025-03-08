<?php

namespace App\Filament\Imports;

use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMappingForNewRecordsOnly()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nisn')
                ->rules(['max:255']),
            ImportColumn::make('nik')
                ->rules(['max:255']),
            ImportColumn::make('tempat_lahir')
                ->rules(['max:255']),
            ImportColumn::make('tanggal_lahir')
                ->rules(['date']),
            ImportColumn::make('jenis_kelamin')
                ->requiredMappingForNewRecordsOnly()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_ayah')
                ->rules(['max:255']),
            ImportColumn::make('nama_ibu')
                ->rules(['max:255']),
            ImportColumn::make('kelas')
                ->requiredMappingForNewRecordsOnly()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('tahunPelajaran')
                ->requiredMappingForNewRecordsOnly()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('status_verval')
                ->boolean()
                ->rules(['boolean']),
            ImportColumn::make('file_foto')
                ->rules(['max:255']),
            ImportColumn::make('file_kk')
                ->rules(['max:255']),
            ImportColumn::make('file_ijazah')
                ->rules(['max:255']),
            ImportColumn::make('nomor_telepon')
                ->rules(['max:255']),
            ImportColumn::make('email')
                ->rules(['email', 'max:255']),
            ImportColumn::make('password')
                ->rules(['max:255']),
            ImportColumn::make('qr_code')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        return Siswa::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'nisn' => $this->data['nisn'],
        ]);

        return new Siswa();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your siswa import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
