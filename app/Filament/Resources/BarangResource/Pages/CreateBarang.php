<?php

namespace App\Filament\Resources\BarangResource\Pages;

use Filament\Notifications\Notification;
use App\Filament\Resources\BarangResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data barang berhasil dibuat');
    }
}
