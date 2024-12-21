<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakturResource\Pages;
use App\Filament\Resources\FakturResource\RelationManagers;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\DetailFaktur;
use App\Models\Faktur;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FakturResource extends Resource
{
    protected static ?string $model = Faktur::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Buat Faktur';
    protected static ?string $navigationGroup = 'Faktur';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('kode_faktur')
                            ->required()
                            ->label('Kode Faktur')
                            ->maxLength(100)
                            ->columnSpan(2),
                        DatePicker::make('tanggal_faktur')
                            ->required()
                            ->label('Tanggal Faktur')
                            ->format('Y-m-d')
                            ->native(false)
                            ->columnSpan(1),
                        Select::make('customer_id')
                            ->reactive()
                            ->required()
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->columnSpan(1)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $customer = Customer::find($state);

                                if ($customer) {
                                    $set('kode_customer', $customer->kode_customer);
                                }
                            }),
                        TextInput::make('kode_customer')
                            ->label('Kode Customer')
                            ->columnSpan(2)
                            ->extraInputAttributes(['readonly' => true]),
                        Repeater::make('detail')
                            ->columnSpan(2)
                            ->label('Detail Faktur')
                            ->relationship('detail')
                            ->schema([
                                Select::make('barang_id')
                                    ->required()
                                    ->label('Barang')
                                    ->relationship('barang', 'name')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $barang = Barang::find($state);

                                        if ($barang) {
                                            $set('nama_barang', $barang->name);
                                            $set('harga', $barang->harga);
                                        }
                                    }),
                                TextInput::make('nama_barang')
                                    ->required()
                                    ->label('Nama Barang')
                                    ->extraInputAttributes(['readonly' => true]),
                                TextInput::make('harga')
                                    ->required()
                                    ->label('Harga')
                                    ->numeric()
                                    ->extraInputAttributes(['readonly' => true])
                                    ->prefix('Rp. '),
                                TextInput::make('qty')
                                    ->reactive()
                                    ->required()
                                    ->label('qty')
                                    ->numeric()
                                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                        $harga = $get('harga');
                                        $set('hasil_qty', $harga * $state);
                                    }),
                                TextInput::make('hasil_qty')
                                    ->required()
                                    ->label('Hasil Qty')
                                    ->numeric(),
                                TextInput::make('diskon')
                                    ->reactive()
                                    ->required()
                                    ->label('Diskon')
                                    ->numeric()
                                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                        $hasilQty = $get('hasil_qty');
                                        $diskon = $hasilQty * ($state / 100);
                                        $set('subtotal', intval($hasilQty - $diskon));
                                    }),
                                TextInput::make('subtotal')
                                    ->required()
                                    ->label('Sub Total')
                                    ->numeric(),
                            ])
                            ->live(),
                        Textarea::make('ket_faktur')
                            ->required()
                            ->label('Keterangan')
                            ->cols(10)
                            ->rows(5)
                            ->columnSpan(2),
                        TextInput::make('total')
                            ->reactive()
                            ->required()
                            ->label('Total')
                            ->numeric()
                            ->columnSpan(2)
                            ->placeholder(function (Set $set, Get $get) {
                                $total = collect($get('detail'))->pluck('subtotal')->sum();
                                if ($total == null) {
                                    $set('total', 0);
                                } else {
                                    $set('total', $total);
                                }
                            }),
                        TextInput::make('nominal_charge')
                            ->reactive()
                            ->required()
                            ->label('Nominal Charge')
                            ->numeric()
                            ->columnSpan(1)
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $total = $get('total');
                                $charge = $total * ($state / 100);
                                $hasil = $total + $charge;
                                $set('total_final', $hasil);
                                $set('charge', $charge);
                            }),
                        TextInput::make('charge')
                            ->required()
                            ->label('Charge')
                            ->extraInputAttributes(['readonly' => true])
                            ->numeric()
                            ->columnSpan(1),
                        TextInput::make('total_final')
                            ->required()
                            ->label('Total Final')
                            ->numeric()
                            ->columnSpan(2),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_faktur')
                    ->label('Kode Faktur'),
                TextColumn::make('tanggal_faktur')
                    ->label('Tanggal Faktur'),
                TextColumn::make('customer.name')
                    ->label('Customer'),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('ID')
                    ->prefix('Rp. ')
                    ->numeric(),
                TextColumn::make('nominal_charge')
                    ->label('Nominal Charge'),
                TextColumn::make('charge')
                    ->label('Charge')
                    ->money('ID')
                    ->prefix('Rp. ')
                    ->numeric(),
                TextColumn::make('total_final')
                    ->label('Total Final')
                    ->money('ID')
                    ->prefix('Rp. ')
                    ->numeric(),
                TextColumn::make('ket_faktur')
                    ->label('Keterangan'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListFakturs::route('/'),
            'create' => Pages\CreateFaktur::route('/create'),
            'edit' => Pages\EditFaktur::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
