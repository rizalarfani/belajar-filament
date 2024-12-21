<?php

namespace App\Filament\Resources\FakturResource\Pages;

use Filament\Actions;
use App\Models\Penjualan;
use Filament\Notifications\Notification;
use App\Filament\Resources\FakturResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaktur extends CreateRecord
{
    protected static string $resource = FakturResource::class;

    protected function afterCreate(): void
    {
        Penjualan::create([
            'kode_penjualan' => $this->record->kode_faktur,
            'tanggal' => $this->record->tanggal_faktur,
            'jumlah' => $this->record->total,
            'customer_id' => $this->record->customer_id,
            'faktur_id' => $this->record->id,
            'status' => 0,
            'keterangan' => $this->record->keterangan,
        ]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Faktur Berhasil dibuat');
    }
}
