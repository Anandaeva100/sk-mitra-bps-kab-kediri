<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratTugasResource\Pages;
use App\Models\SuratTugas;
use App\Models\SurveyActivity;
use App\Models\MonitoringSurvey;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $set('nama_pcl', null);
                                    $set('wilayah_tugas', null);

                                    // Mengisi otomatis Textarea 'untuk' saat Nama Kegiatan dipilih
                                    if ($state) {
                                        $set('untuk', "Melaksanakan Pendataan {$state} di wilayah kerja yang telah ditentukan.");
                                    } else {
                                        $set('untuk', null);
                                    }
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
    ->description('Klik tombol di kanan untuk mengelola atau mengedit dasar hukum.')
    ->headerActions([
        // Tombol Modal Pop-up untuk Mengedit/Menambah Dasar Hukum
        FormAction::make('kelola_mengingat')
            ->label('Tambah / Kelola Dasar Hukum')
            ->icon('heroicon-o-pencil-square')
            ->color('warning')
            ->modalHeading('Kelola Dasar Hukum (Mengingat)')
            ->modalSubmitActionLabel('Simpan Dasar Hukum')
            ->fillForm(fn ($record, Get $get) => [
                'mengingat_modal' => $get('mengingat') ?: [
                    ['poin' => 'UU No. 16 Tahun 1997 tentang Statistik;'],
                    ['poin' => 'Undang-Undang Nomor 6 Tahun 2014 tentang Desa;'],
                    ['poin' => 'Undang-Undang Nomor 23 Tahun 2014 tentang Pemerintahan Daerah sebagaimana diubah beberapa kali terakhir dengan Undang-Undang Nomor 9 Tahun 2015 tentang Perubahan Kedua atas Undang-Undang Nomor 23 Tahun 2014 tentang Pemerintahan Daerah;'],
                    ['poin' => 'Peraturan Pemerintah Nomor 51 Tahun 1999 tentang Penyelenggaraan Statistik;'],
                    ['poin' => 'Peraturan Presiden Republik Indonesia Nomor 86 Tahun 2007 tentang Badan Pusat Statistik;'],
                    ['poin' => 'Peraturan Badan Pusat Statistik Nomor 2 Tahun 2025 tentang Organisasi dan Tata Kerja Badan Pusat Statistik;'],
                ],
            ])
            ->form([
                Repeater::make('mengingat_modal')
                    ->hiddenLabel()
                    ->itemLabel(function (array $state, Repeater $component): ?string {
                        $items = array_values($component->getState() ?? []);
                        $index = array_search($state, $items, true);

                        return 'Dasar Hukum ' . ($index !== false ? $index + 1 : '');
                    })
                    ->schema([
                        Textarea::make('poin')
                            ->hiddenLabel()
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->addAction(
                        fn (\Filament\Forms\Components\Actions\Action $action) => $action
                            ->label('Tambah Dasar Hukum Baru')
                            ->icon('heroicon-o-plus')
                    )
                    ->reorderable()
                    ->deletable(true)
                    ->collapsible(false)
                    ->columnSpanFull(),
            ])
            ->action(function (array $data, Set $set) {
                // Menyimpan hasil editan modal ke field 'mengingat'
                $set('mengingat', $data['mengingat_modal']);
            }),

        // Tombol Preview PDF
        FormAction::make('preview_pdf')
            ->label('Preview Surat')
            ->icon('heroicon-o-eye')
            ->color('info')
            ->url(fn ($record) => $record ? route('surat-tugas.pdf', $record) : null)
            ->openUrlInNewTab()
            ->visible(fn ($record) => $record !== null),
    ])
    ->schema([
        // Hidden input untuk menyimpan state array dasar hukum dari modal
        Hidden::make('mengingat')
            ->default([
                ['poin' => 'UU No. 16 Tahun 1997 tentang Statistik;'],
                ['poin' => 'Undang-Undang Nomor 6 Tahun 2014 tentang Desa;'],
                ['poin' => 'Undang-Undang Nomor 23 Tahun 2014 tentang Pemerintahan Daerah sebagaimana diubah beberapa kali terakhir dengan Undang-Undang Nomor 9 Tahun 2015 tentang Perubahan Kedua atas Undang-Undang Nomor 23 Tahun 2014 tentang Pemerintahan Daerah;'],
                ['poin' => 'Peraturan Pemerintah Nomor 51 Tahun 1999 tentang Penyelenggaraan Statistik;'],
                ['poin' => 'Peraturan Presiden Republik Indonesia Nomor 86 Tahun 2007 tentang Badan Pusat Statistik;'],
                ['poin' => 'Peraturan Badan Pusat Statistik Nomor 2 Tahun 2025 tentang Organisasi dan Tata Kerja Badan Pusat Statistik;'],
            ]),
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
                            ->default(function (Get $get) {
                                $namaSurvei = $get('nama_survei');

                                if ($namaSurvei) {
                                    return "Melaksanakan Pendataan {$namaSurvei} di wilayah kerja yang telah ditentukan.";
                                }

                                return "Melaksanakan Pendataan di wilayah kerja yang telah ditentukan.";
                            })
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

                TextColumn::make('waktu_tugas')
                    ->label('Waktu / Rentang Tugas')
                    ->limit(35)
                    ->tooltip(
                        fn ($record) => $record->waktu_tugas
                    )
                    ->searchable(),

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
                    ->icon('heroicon-o-printer')
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