<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Models\Pegawai;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'MASTER DATA';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Daftar Pegawai BPS';

    protected static ?string $modelLabel = 'Pegawai';

    protected static ?string $pluralModelLabel = 'Daftar Pegawai BPS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('nama')
                    ->label('Nama Pegawai')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->unique(
                        table: 'pegawais',
                        column: 'nip',
                        ignoreRecord: true
                    )
                    ->maxLength(30),

                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(255),

                TextInput::make('golongan_ruang')
                    ->label('Golongan / Ruang')
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->searchPlaceholder('Cari nama, NIP, atau jabatan...')

            ->columns([

                TextColumn::make('no')
                    ->label('No.')
                    ->alignCenter()
                    ->rowIndex(),

                TextColumn::make('nama')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('golongan_ruang')
                    ->label('Golongan / Ruang')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->alignCenter(),

            ])

            ->filters([

                SelectFilter::make('jabatan')
                    ->label('Jabatan')
                    ->options(
                        fn () => Pegawai::query()
                            ->whereNotNull('jabatan')
                            ->distinct()
                            ->orderBy('jabatan')
                            ->pluck('jabatan', 'jabatan')
                            ->toArray()
                    )
                    ->searchable(),

                SelectFilter::make('golongan_ruang')
                    ->label('Golongan / Ruang')
                    ->options(
                        fn () => Pegawai::query()
                            ->whereNotNull('golongan_ruang')
                            ->distinct()
                            ->orderBy('golongan_ruang')
                            ->pluck(
                                'golongan_ruang',
                                'golongan_ruang'
                            )
                            ->toArray()
                    )
                    ->searchable(),

                TernaryFilter::make('is_active')
                    ->label('Status Pegawai')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),

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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListPegawais::route('/'),

            'create' => Pages\CreatePegawai::route('/create'),

            'edit' => Pages\EditPegawai::route('/{record}/edit'),

        ];
    }
}