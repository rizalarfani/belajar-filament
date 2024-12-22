<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class BarangChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $getData = $this->getChartBarang();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang per bulan',
                    'data' => $getData['jumlah']
                ],
            ],
            'labels' => $getData['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getChartBarang(): array
    {
        $now = Carbon::now();
        $barangs = [];

        $bulans = collect(range(1, 12))->map(function ($bulan) use ($now, $barangs) {
            $jumlah = Barang::whereMonth('created_at', Carbon::parse($now->month($bulan)->format('Y-m')))->count();
            $barangs[] = $jumlah;

            return $now->month($bulan)->format('M');
        })->toArray();

        return [
            'labels' => $bulans,
            'jumlah' => $barangs,
        ];
    }
}
