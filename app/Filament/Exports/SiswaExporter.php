<?php

namespace App\Filament\Exports;

use App\Models\Siswa;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SiswaExporter extends Exporter
{
    protected static ?string $model = Siswa::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('nik')
            ->label('NIK'),
            ExportColumn::make('nisn')
            ->label('NISN'),
            ExportColumn::make('nama')
            ->label('Nama Lengkap'),
            ExportColumn::make('tempat_lahir')
            ->label('Tempat Lahir'),
            ExportColumn::make('tanggal_lahir')
            ->label('Tanggal Lahir'),
            ExportColumn::make('jenis_kelamin')
            ->label('Jenis Kelamin'),
            ExportColumn::make('nama_ayah')
            ->label('Nama Ayah'),
            ExportColumn::make('nama_ibu')
            ->label('Nama Ibu'),
            ExportColumn::make('kelas.nama')
            ->label('Kelas'),
            ExportColumn::make('tahunPelajaran.nama')
            ->label('Tahun Pelajaran'),
            ExportColumn::make('nomor_telepon')
            ->label('Nomor Telepon'),
            ExportColumn::make('email')
            ->label('Email'),
            ExportColumn::make('status_verval')
            ->label('Status Verifikasi'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your siswa export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
