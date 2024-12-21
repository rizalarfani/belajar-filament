<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Customer';

    protected static ?string $navigationGroup = 'Kelola';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('kode_customer')
                            ->required()
                            ->label('Kode Customer')
                            ->minLength(5)
                            ->maxLength(50)
                            ->placeholder('Masukkan KOde Customer'),
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Lengkap')
                            ->minLength(5)
                            ->maxLength(255)
                            ->placeholder('Masukkan nama lengkap'),
                        TextInput::make('email')
                            ->required()
                            ->label('Email')
                            ->email()
                            ->placeholder('Masukkan email'),
                        TextInput::make('no_hp')
                            ->required()
                            ->label('No. Handphone')
                            ->placeholder('Masukkan no. handphone'),
                        Textarea::make('alamat')
                            ->required()
                            ->label('Alamat anda')
                            ->cols(10)
                            ->rows(5),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Contact')
                    ->description(fn(Customer $recortd): string => $recortd->no_hp),
                TextColumn::make('alamat')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
