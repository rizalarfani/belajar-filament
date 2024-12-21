<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Barang';
    protected static ?string $navigationGroup = 'Kelola';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        TextInput::make('kode')
                            ->required()
                            ->label('Kode Barang')
                            ->placeholder('Masukan Kode Barang'),
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Barang')
                            ->placeholder('Masukan Nama Barang'),
                        Textarea::make('description')
                            ->required()
                            ->rows(10)
                            ->cols(5)
                            ->label('Description')
                            ->placeholder('Masukan Deskripsi Barang'),
                        TextInput::make('harga')
                            ->required()
                            ->integer()
                            ->label('Harga')
                            ->placeholder('Masukan Harga')
                            ->prefix('Rp. '),
                        TextInput::make('stock')
                            ->required()
                            ->integer()
                            ->label('Stock')
                            ->placeholder('Masukan Stock')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->icon('heroicon-m-clipboard')
                    ->copyable()
                    ->copyMessage('Berhasil salin kode produk')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->description(fn(Barang $record): string => $record->description),
                TextColumn::make('stock')
                    ->badge()
                    ->color(static function ($state): string {
                        if ($state <= 5) {
                            return 'danger';
                        } else if ($state >  5 && $state <= 10) {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->numeric(),
                TextColumn::make('harga')
                    ->money('ID')
                    ->prefix('Rp. ')
                    ->numeric(),
            ])
            ->emptyStateHeading('Barang tidak ditemukan')
            ->emptyStateDescription('Klik tombol di bawah untuk membuat barang baru')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Buat Baru')
                    ->url(url('admin/barangs/create'))
                    ->icon('heroicon-m-plus')
                    ->button()
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
