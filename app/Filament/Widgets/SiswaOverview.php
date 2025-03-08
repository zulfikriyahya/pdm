<?php

namespace App\Filament\Widgets;

use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class SiswaOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $tahunAktif = TahunPelajaran::where('is_active', true)->first();
        if ($tahunAktif) {
            $total_siswa = Siswa::where('tahun_pelajaran_id', $tahunAktif->id)->count();
            $total_verval = Siswa::where('tahun_pelajaran_id', $tahunAktif->id)->where('status_verval', true)->count();
            $total_unverval = Siswa::where('tahun_pelajaran_id', $tahunAktif->id)->where('status_verval', false)->count();
            return [
                Stat::make('Total Siswa', $total_siswa . ' Siswa')
                    ->icon('heroicon-m-academic-cap')
                    ->color('primary'),
                Stat::make('Sudah Verifikasi', $total_verval . ' Siswa')
                    ->icon('heroicon-m-check-badge')
                    ->color('success'),
                Stat::make('Belum Verifikasi', $total_unverval . ' Siswa')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger'),
            ];
        }
        return [];
    }
}
