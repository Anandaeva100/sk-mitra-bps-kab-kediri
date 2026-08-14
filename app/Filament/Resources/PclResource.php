<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PclResource\Pages;
use App\Models\Pcl;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PclResource extends Resource
{
    protected static ?string $model = Pcl::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Daftar PCL';
    protected static ?string $modelLabel = 'PCL';
    protected static ?string $pluralModelLabel = 'Daftar PCL';
    protected static ?string $navigationGroup = 'MASTER DATA';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi PCL')
                    ->schema([
                        // Inputan ID PCL ditambahkan di sini
                        TextInput::make('id')
                            ->label('ID PCL')
                            ->required()
                            ->numeric()
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit'), // Tetap di-disable saat Edit agar ID tidak terubah tak sengaja

                        TextInput::make('nama_pcl')
                            ->label('Nama PCL')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No.')
                    ->alignCenter()
                    ->rowIndex(),

                TextColumn::make('id')
                    ->label('ID PCL')
                    ->color('gray')
                    ->icon('heroicon-m-square-2-stack')
                    ->iconPosition('after')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('ID PCL berhasil disalin'),

                TextColumn::make('nama_pcl')
                    ->label('Nama PCL')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotificationTitle('Data PCL berhasil dihapus'),
            ])
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPcls::route('/'),
            'create' => Pages\CreatePcl::route('/create'),
            'edit' => Pages\EditPcl::route('/{record}/edit'),
        ];
    }
}