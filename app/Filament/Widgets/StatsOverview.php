<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Penjualan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Customer', Customer::count())
                ->description('Total number of customer in the system')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Barang', Barang::count())
                ->description('Total number of Barang')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),
            Stat::make('Penjualan Lunas', Penjualan::where('status', 1)->count())
                ->description('Total number of Penjualan Lunas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Penjualan Belum Lunas', Penjualan::where('status', 0)->count())
                ->description('Total number of Penjualan Belum Lunas')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
        ];
    }
}
