<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TingkatOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $tahunAktif = TahunPelajaran::where('is_active', true)->first();
        if ($tahunAktif) {
            $total_kelas = Kelas::where('tahun_pelajaran_id', $tahunAktif->id)->count();
            $tingkat_vii = Kelas::where('tahun_pelajaran_id', $tahunAktif->id)->where('tingkat', 'VII')->count();
            $tingkat_viii = Kelas::where('tahun_pelajaran_id', $tahunAktif->id)->where('tingkat', 'VIII')->count();
            $tingkat_ix = Kelas::where('tahun_pelajaran_id', $tahunAktif->id)->where('tingkat', 'IX')->count();

            return [
                Stat::make('Total Rombel/Kelas', $total_kelas . ' Kelas')
                    ->icon('heroicon-m-building-storefront')
                    ->color('primary'),
                Stat::make('Tingkat VII', $tingkat_vii . ' Kelas')
                    ->icon('heroicon-m-building-office-2')
                    ->color('success'),
                Stat::make('Tingkat VIII', $tingkat_viii . ' Kelas')
                    ->icon('heroicon-m-building-office-2')
                    ->color('success'),
                Stat::make('Tingkat IX', $tingkat_ix . ' Kelas')
                    ->icon('heroicon-m-building-office-2')
                    ->color('success'),
            ];
        }
        return [];
    }
}
