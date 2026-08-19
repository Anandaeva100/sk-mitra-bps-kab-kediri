<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratPerjanjianKerjaResource\Pages;
use App\Models\SuratPerjanjianKerja;
use App\Models\Pcl;
use App\Models\MonitoringSurvey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

class SuratPerjanjianKerjaResource extends Resource
{
    protected static ?string $model = SuratPerjanjianKerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'DOKUMEN';
    
    protected static ?string $navigationLabel = 'Surat Perjanjian Kerja';
    protected static ?string $modelLabel = 'Surat Perjanjian Kerja';
    protected static ?string $pluralModelLabel = 'Surat Perjanjian Kerja';
    protected static ?string $breadcrumb = 'Surat Perjanjian Kerja';
    protected static ?string $slug = 'surat-perjanjian-kerja';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat Perjanjian Kerja')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_spk')
                            ->label('Nomor SPK')
                            ->placeholder('Contoh: PPIS-007.3/2910/KSA-JAGUNG/02/2026')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nama_ppk')
                            ->label('Nama Pihak Pertama (PPK)')
                            ->placeholder('Contoh: Hariyanti Ika Setyabudi, SE')
                            ->default('Hariyanti Ika Setyabudi, SE')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('survey_activity_id')
                            ->label('Pilih Kegiatan')
                            ->relationship('surveyActivity', 'nama_kegiatan')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_spk')
                            ->label('Tanggal SPK')
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required(),

                        Forms\Components\Textarea::make('uraian_tugas')
                            ->label('Uraian Tugas')
                            ->placeholder('Contoh: Pendataan dan Pengambilan Foto Amatan pada Segmen Terpilih')
                            ->default('Pendataan dan Pengambilan Foto Amatan pada Segmen Terpilih')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('satuan')
                            ->label('Satuan')
                            ->placeholder('Contoh: Segmen')
                            ->default('Segmen')
                            ->maxLength(50)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('beban_anggaran')
                            ->label('Beban Anggaran')
                            ->placeholder('Contoh: 2910.BMA.007.005.521213')
                            ->maxLength(255)
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Jangka Waktu Perjanjian Kerja')
                    ->description('Tentukan periode awal (tanggal mulai) dan akhir (tanggal selesai) pelaksanaan tugas.')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->placeholder('Pilih Tanggal Mulai')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->placeholder('Pilih Tanggal Selesai')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Rincian Alamat Pihak Kedua (PCL)')
                    ->schema([
                        Forms\Components\Select::make('pcl_id')
                            ->label('Nama PCL (Pihak Kedua)')
                            ->options(function () {
                                return Pcl::pluck('nama_pcl', 'id_pcl')->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Nama PCL')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('alamat_pcl')
                            ->label('Alamat Lengkap PCL')
                            ->placeholder('Contoh: RT 001 RW 002 Dusun Karangrejo Desa Karangrejo, Kecamatan Kandat, Kabupaten Kediri')
                            ->helperText('Isi lengkap rincian RT, RW, Dusun, Desa/Kelurahan, Kecamatan, dan Kabupaten PCL.')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_spk')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_ppk')
                    ->label('Nama PPK')
                    ->searchable()
                    ->sortable()
                    ->default('-'),

                Tables\Columns\TextColumn::make('surveyActivity.nama_kegiatan')
                    ->label('Nama Survei')
                    ->searchable()
                    ->sortable()
                    ->default('-'),

                Tables\Columns\TextColumn::make('nama_pcl_display')
                    ->label('Nama PCL')
                    ->getStateUsing(function (SuratPerjanjianKerja $record) {
                        if (Schema::hasColumn('surat_perjanjian_kerja', 'pcl_id') && !empty($record->pcl_id)) {
                            $pclDirect = Pcl::query();
                            if (Schema::hasColumn('pcls', 'id_pcl')) {
                                $pclDirect->where('id_pcl', $record->pcl_id);
                            } else {
                                $pclDirect->where('id', $record->pcl_id);
                            }
                            
                            $foundPcl = $pclDirect->first();
                            if ($foundPcl) {
                                return $foundPcl->nama_pcl;
                            }
                        }

                        if (!empty($record->survey_activity_id) && Schema::hasTable('monitoring_surveys')) {
                            $monitoringQuery = MonitoringSurvey::query();
                            
                            if (Schema::hasColumn('monitoring_surveys', 'survey_activity_id')) {
                                $monitoringQuery->where('survey_activity_id', $record->survey_activity_id);
                            } elseif (Schema::hasColumn('monitoring_surveys', 'kegiatan_id')) {
                                $monitoringQuery->where('kegiatan_id', $record->survey_activity_id);
                            } elseif (Schema::hasColumn('monitoring_surveys', 'id_kegiatan')) {
                                $monitoringQuery->where('id_kegiatan', $record->survey_activity_id);
                            } else {
                                return '-';
                            }

                            $monitoring = $monitoringQuery->first();

                            if ($monitoring && !empty($monitoring->pcl_id)) {
                                $pclQuery = Pcl::query();
                                if (Schema::hasColumn('pcls', 'id_pcl')) {
                                    $pclQuery->where('id_pcl', $monitoring->pcl_id);
                                } else {
                                    $pclQuery->where('id', $monitoring->pcl_id);
                                }

                                $pcl = $pclQuery->first();
                                return $pcl ? $pcl->nama_pcl : '-';
                            }
                        }

                        return '-';
                    })
                    ->default('-'),

                Tables\Columns\TextColumn::make('jangka_waktu')
                    ->label('Jangka Waktu')
                    ->getStateUsing(function (SuratPerjanjianKerja $record) {
                        if ($record->tanggal_mulai && $record->tanggal_selesai) {
                            return $record->tanggal_mulai->translatedFormat('d M Y') . ' s/d ' . $record->tanggal_selesai->translatedFormat('d M Y');
                        }
                        return '-';
                    }),

                Tables\Columns\TextColumn::make('tanggal_spk')
                    ->label('Tanggal Surat')
                    ->date('d F Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('survey_activity_id')
                    ->label('Kegiatan')
                    ->relationship('surveyActivity', 'nama_kegiatan')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Action::make('cetak_pdf')
                    ->label('Cetak PDF')
                    ->color('success')
                    ->icon('heroicon-o-printer')
                    ->url(fn (SuratPerjanjianKerja $record) => route('spk.cetak-pdf', ['id' => $record->id]))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('cetak_banyak_pdf')
                        ->label('Cetak SPK Terpilih')
                        ->color('success')
                        ->icon('heroicon-o-printer')
                        ->action(function (Collection $records) {
                            $ids = $records->pluck('id')->implode(',');
                            return redirect()->to(route('spk.cetak-pdf', ['ids' => $ids]));
                        }),

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
            'index' => Pages\ListSuratPerjanjianKerja::route('/'),
            'create' => Pages\CreateSuratPerjanjianKerja::route('/create'),
            'edit' => Pages\EditSuratPerjanjianKerja::route('/{record}/edit'),
        ];
    }
}