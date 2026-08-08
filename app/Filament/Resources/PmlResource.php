<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PmlResource\Pages;
use App\Models\Pml;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class PmlResource extends Resource
{
    protected static ?string $model = Pml::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Daftar PML';

    protected static ?string $modelLabel = 'PML';

    protected static ?string $pluralModelLabel = 'Daftar PML';

    protected static ?string $navigationGroup = 'MASTER DATA';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi PML')
                    ->schema([
                        TextInput::make('nama_pml')
                            ->label('Nama PML')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('nama_pml', 'asc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No.')
                    ->alignCenter()
                    ->rowIndex(),

                TextColumn::make('nama_pml')
                    ->label('Nama PML')
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
            'index' => Pages\ListPmls::route('/'),
            'create' => Pages\CreatePml::route('/create'),
            'edit' => Pages\EditPml::route('/{record}/edit'),
        ];
    }
}