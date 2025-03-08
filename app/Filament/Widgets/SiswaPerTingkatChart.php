<?php

namespace App\Filament\Widgets;

use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Filament\Widgets\ChartWidget;

class SiswaPerTingkatChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Siswa Per Tingkat';

    protected function getData(): array
    {
        $tahunAktif = TahunPelajaran::where('is_active', true)->first();

        if ($tahunAktif) {
            // Mengelompokkan data siswa berdasarkan tingkat dan menghitung jumlahnya
            $data = Siswa::selectRaw('kelas.tingkat, COUNT(siswas.id) as jumlah')
                ->join('kelas', 'kelas.id', '=', 'siswas.kelas_id')
                ->where('kelas.tahun_pelajaran_id', $tahunAktif->id)
                ->groupBy('kelas.tingkat')
                ->pluck('jumlah', 'kelas.tingkat');

            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah Siswa',
                        'data' => [$data['VII'] ?? 0, $data['VIII'] ?? 0, $data['IX'] ?? 0],
                        'backgroundColor' => ['rgba(75, 192, 192, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                        'borderColor' => ['rgba(75, 192, 192, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                        'borderWidth' => 3,
                    ],
                ],
                'labels' => ['Tingkat VII', 'Tingkat VIII', 'Tingkat IX'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => [0, 0, 0],
                    'backgroundColor' => ['rgba(75, 192, 192, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    'borderColor' => ['rgba(75, 192, 192, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                    'borderWidth' => 3,
                ],
            ],
            'labels' => ['Tingkat VII', 'Tingkat VIII', 'Tingkat IX'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
