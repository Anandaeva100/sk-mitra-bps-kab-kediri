<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PclResource\Pages;
use App\Models\Pcl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

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
                        TextInput::make('id_pcl')
                            ->label('ID PCL')
                            ->required()
                            ->maxLength(9)
                            ->unique(ignoreRecord: true),

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

                TextColumn::make('id_pcl')
                    ->label('ID PCL')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_pcl')
                    ->label('Nama PCL')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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