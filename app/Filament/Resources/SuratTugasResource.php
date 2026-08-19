<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratTugasResource\Pages;
use App\Models\SuratTugas;
use App\Models\SurveyActivity;
use App\Models\MonitoringSurvey;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;

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

                Section::make('Informasi Surat Tugas')
                    ->schema([

                        TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(
                                'Contoh: 100.1/3506/SS.220/2026'
                            ),

                        Select::make('nama_survei')
                            ->label('Nama Kegiatan / Survei')
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
                            ->live()
                            ->afterStateUpdated(function ($set) {
                                $set('nama_pcl', null);
                                $set('wilayah_tugas', null);
                            })
                            ->required(),

                        DatePicker::make('tanggal_surat')
                            ->label('Tanggal Surat')
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required(),

                    ])
                    ->columns(2),
                
                Section::make('Mengingat')
                    ->description('Tambahkan dasar hukum jika diperlukan.')
                    ->schema([

                        Repeater::make('mengingat')
                            ->label('Dasar Hukum Tambahan')
                            ->schema([

                                Textarea::make('isi')
                                    ->label('Isi Dasar Hukum')
                                    ->placeholder(
                                        'Contoh: UU No. 16 Tahun 1997 tentang Statistik'
                                    )
                                    ->required()
                                    ->rows(2)
                                    ->autosize(),

                            ])
                            ->defaultItems(0)
                            ->addAction(
                                fn ($action) => $action
                                    ->label('Tambah Dasar Hukum')
                                    ->icon('heroicon-m-plus')
                            )
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(
                                fn (array $state): ?string =>
                                    ! empty($state['isi'])
                                        ? str($state['isi'])->limit(60)
                                        : 'Dasar Hukum'
                            )
                            ->columnSpanFull(),

                    ])
                    ->columns(1),

                Section::make('Informasi PCL')
                    ->schema([

                        Select::make('nama_pcl')
                            ->label('Nama PCL')
                            ->options(function ($get) {

                                $namaSurvei = $get('nama_survei');

                                if (! $namaSurvei) {
                                    return [];
                                }

                                return MonitoringSurvey::query()
                                    ->where('nama_kegiatan', $namaSurvei)
                                    ->whereNotNull('nama_pcl')
                                    ->orderBy('nama_pcl')
                                    ->distinct()
                                    ->pluck(
                                        'nama_pcl',
                                        'nama_pcl'
                                    )
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->disabled(fn ($get) => ! $get('nama_survei'))
                            ->afterStateUpdated(function ($state, $set, $get) {

                                if (! $state) {
                                    $set('wilayah_tugas', null);
                                    return;
                                }

                                $namaSurvei = $get('nama_survei');

                                $wilayah = MonitoringSurvey::query()
                                    ->where('nama_kegiatan', $namaSurvei)
                                    ->where('nama_pcl', $state)
                                    ->value('wilayah_tugas');

                                $set('wilayah_tugas', $wilayah);
                            }),

                    ])
                    ->columns(1),

                Section::make('Detail Penugasan')
                    ->schema([

                        Textarea::make('untuk')
                            ->label('Untuk')
                            ->required()
                            ->rows(3)
                            ->placeholder(
                                'Contoh: Untuk melaksanakan kegiatan IBS Triwulan 4 di wilayah kerja yang telah ditentukan.'
                            )
                            ->columnSpanFull(),

                        TextInput::make('wilayah_tugas')
                            ->label('Wilayah Tugas')
                            ->required()
                            ->placeholder(
                                'Contoh: Kecamatan Puncu dan Kecamatan Badas'
                            )
                            ->helperText(
                                'Wilayah akan diisi otomatis berdasarkan Nama PCL, tetapi dapat diubah jika diperlukan.'
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

                TextColumn::make('nama_pcl')
                    ->label('Nama PCL')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('wilayah_tugas')
                    ->label('Wilayah Tugas')
                    ->limit(40)
                    ->tooltip(
                        fn ($record) => $record->wilayah_tugas
                    ),

                TextColumn::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->date('d F Y')
                    ->sortable(),

            ])

            ->filters([

                /*
                |--------------------------------------------------------------------------
                | KEGIATAN
                |--------------------------------------------------------------------------
                */

                SelectFilter::make('nama_survei')
                    ->label('Kegiatan')
                    ->options(function () {

                        return SuratTugas::query()
                            ->whereNotNull('nama_survei')
                            ->where('nama_survei', '!=', '')
                            ->select('nama_survei')
                            ->distinct()
                            ->orderBy('nama_survei')
                            ->pluck(
                                'nama_survei',
                                'nama_survei'
                            )
                            ->toArray();

                    })
                    ->searchable()
                    ->preload()
                    ->native(false),


                /*
                |--------------------------------------------------------------------------
                | NAMA PCL
                |--------------------------------------------------------------------------
                */

                SelectFilter::make('nama_pcl')
                    ->label('Nama PCL')
                    ->options(function () {

                        return SuratTugas::query()
                            ->whereNotNull('nama_pcl')
                            ->where('nama_pcl', '!=', '')
                            ->select('nama_pcl')
                            ->distinct()
                            ->orderBy('nama_pcl')
                            ->pluck(
                                'nama_pcl',
                                'nama_pcl'
                            )
                            ->toArray();

                    })
                    ->searchable()
                    ->preload()
                    ->native(false),


                /*
                |--------------------------------------------------------------------------
                | TAHUN
                |--------------------------------------------------------------------------
                */

                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(function () {

                        return SuratTugas::query()
                            ->whereNotNull('tanggal_surat')
                            ->selectRaw('YEAR(tanggal_surat) as tahun')
                            ->distinct()
                            ->orderByDesc('tahun')
                            ->pluck(
                                'tahun',
                                'tahun'
                            )
                            ->toArray();

                    })
                    ->native(false)
                    ->query(function ($query, array $data) {

                        if (! empty($data['value'])) {

                            $query->whereYear(
                                'tanggal_surat',
                                $data['value']
                            );

                        }

                    }),

            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            
            ->actions([

                Tables\Actions\Action::make('pdf')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(
                        fn ($record) => route(
                            'surat-tugas.pdf',
                            $record
                        )
                    )
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
        return [];
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