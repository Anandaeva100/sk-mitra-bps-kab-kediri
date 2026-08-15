<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratTugasResource\Pages;
use App\Filament\Resources\SuratTugasResource\RelationManagers;
use App\Models\SuratTugas;
use App\Models\Pml;
use App\Models\Pcl;
use App\Models\SurveyActivity;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratTugasResource extends Resource
{
    protected static ?string $model = SuratTugas::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Surat Tugas';

    protected static ?string $modelLabel = 'Surat Tugas';

    protected static ?string $pluralModelLabel = 'Surat Tugas';

    protected static ?string $navigationGroup = 'DOKUMEN';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Informasi Surat')
                    ->schema([

                        TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: 100.1/3506/SS.220/2026'),

                        Select::make('nama_survei')
                            ->label('Nama Survei')
                            ->options(function () {
                                return SurveyActivity::query()
                                    ->where('status', 'Aktif')
                                    ->orderBy('nama_kegiatan')
                                    ->pluck(
                                        'nama_kegiatan',
                                        'nama_kegiatan'
                                    )
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('tanggal_surat')
                            ->label('Tanggal Surat')
                            ->default(now())
                            ->displayFormat('d F Y')
                            ->required(),

                    ])
                    ->columns(2),

                Section::make('Informasi Mitra')
                    ->schema([

                        Select::make('jenis_mitra')
                            ->label('Jenis Mitra')
                            ->options([
                                'PML' => 'PML',
                                'PCL' => 'PCL',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($set) {
                                $set('nama_mitra', null);
                            })
                            ->required(),

                        Select::make('nama_mitra')
                            ->label('Nama Mitra')
                            ->options(function ($get) {

                                $jenis = $get('jenis_mitra');

                                if ($jenis === 'PML') {
                                    return Pml::query()
                                        ->orderBy('nama_pml')
                                        ->pluck(
                                            'nama_pml',
                                            'nama_pml'
                                        )
                                        ->toArray();
                                }

                                if ($jenis === 'PCL') {
                                    return Pcl::query()
                                        ->orderBy('nama_pcl')
                                        ->pluck(
                                            'nama_pcl',
                                            'nama_pcl'
                                        )
                                        ->toArray();
                                }

                                return [];
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($get) => ! $get('jenis_mitra')),

                    ])
                    ->columns(2),

                Section::make('Detail Penugasan')
                    ->schema([

                        Textarea::make('wilayah_tugas')
                            ->label('Wilayah Tugas')
                            ->required()
                            ->rows(3)
                            ->placeholder(
                                'Contoh: Kecamatan Pare dan Kecamatan Kandangan'
                            ),

                        TextInput::make('waktu_tugas')
                            ->label('Waktu / Rentang Tugas')
                            ->required()
                            ->placeholder(
                                'Contoh: 15 – 16 Juni 2026'
                            ),

                    ])
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('no')
                    ->label('No.')
                    ->rowIndex()
                    ->alignCenter(),

                TextColumn::make('nomor_surat')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_survei')
                    ->label('Nama Survei')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_mitra')
                    ->label('Jenis Mitra')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PML' => 'info',
                        'PCL' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('nama_mitra')
                    ->label('Nama Mitra')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('wilayah_tugas')
                    ->label('Wilayah Tugas')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->wilayah_tugas),

                TextColumn::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->date('d F Y')
                    ->sortable(),

            ])
            ->actions([

                Tables\Actions\Action::make('pdf')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn ($record) => route(
                        'surat-tugas.pdf',
                        $record
                    ))
                    ->openUrlInNewTab(),

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
            'index' => Pages\ListSuratTugas::route('/'),
            'create' => Pages\CreateSuratTugas::route('/create'),
            'edit' => Pages\EditSuratTugas::route('/{record}/edit'),
        ];
    }
}
