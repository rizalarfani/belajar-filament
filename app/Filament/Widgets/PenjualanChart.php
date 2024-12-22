<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $getData = Penjualan::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan',
                    'data' => array_values($getData),
                ]
            ],
            'labels' => ['Belum Lunas', 'Lunas'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
