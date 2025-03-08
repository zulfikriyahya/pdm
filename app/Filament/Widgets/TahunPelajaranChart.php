<?php

namespace App\Filament\Widgets;

use App\Models\Siswa;
use Flowframe\Trend\Trend;
use App\Models\TahunPelajaran;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TahunPelajaranChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Siswa Per Tahun';

    protected function getData(): array
    {
        $tahunAktif = TahunPelajaran::where('is_active', true)->first();

        if ($tahunAktif) {
            [$startYear, $endYear] = explode('/', $tahunAktif->nama);
            // Mengubah string tahun menjadi integer
            $startYear = (int) $startYear;
            $endYear = (int) $endYear;
            // Membuat rentang tahun untuk analisis 
            $startDate = now()->setYear($startYear)->startOfYear();
            $endDate = now()->setYear($endYear)->endOfYear();

            $data = Trend::model(Siswa::class)
                ->between(
                    start: $startDate,
                    end: $endDate,
                )
                ->perYear()
                ->count('id');

            return [
                'datasets' => [
                    [
                        'label' => 'Jumlah Siswa',
                        'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'borderWidth' => 1,
                    ],
                ],
                'labels' => $data->map(fn(TrendValue $value) => $value->date),
            ];
        }
        return [
            'datasets' => [],
            'labels' => [],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
