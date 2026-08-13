<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringHonorResource\Pages;
use App\Models\MonitoringSurvey;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MonitoringHonorResource extends Resource
{
    protected static ?string $model = MonitoringSurvey::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Monitoring Honor';

    protected static ?string $modelLabel = 'Monitoring Honor';

    protected static ?string $pluralModelLabel = 'Monitoring Honor';

    protected static ?string $navigationGroup = 'MONITORING';

    protected static ?int $navigationSort = 3;

    /**
     * Ambil Batas Honor secara Sinkron dari Cache atau Database Settings
     */
    public static function getBatasHonor(): int
    {
        return Cache::rememberForever('app_batas_honor', function () {
            $settingValue = Setting::get('batas_honor', '3078000');
            // Bersihkan format titik/koma agar aman dihitung dalam query integer
            $cleanNominal = preg_replace('/[^0-9]/', '', (string) $settingValue);
            return (int) ($cleanNominal ?: 3078000);
        });
    }

    /**
     * Badge jumlah mitra yang melebihi batas honor
     */
    public static function getNavigationBadge(): ?string
    {
        $batasHonor = self::getBatasHonor();

        $count = DB::table('monitoring_surveys')
            ->select('nama_pcl', 'bulan')
            ->groupBy('nama_pcl', 'bulan')
            ->havingRaw('SUM(honor_total) >= ?', [$batasHonor])
            ->get()
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function getEloquentQuery(): Builder
    {
        $orderBulan = "FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')";

        return parent::getEloquentQuery()
            ->select([
                DB::raw('MIN(id) as id'),
                'bulan',
                'nama_pcl',
                DB::raw('COUNT(DISTINCT CONCAT(bulan, "-", nama_kegiatan)) as jumlah_kegiatan'),
                DB::raw('SUM(beban_banyak) as total_beban'),
                DB::raw('SUM(honor_total) as total_honor'),
            ])
            ->groupBy('bulan', 'nama_pcl')
            ->orderByRaw($orderBulan)
            ->orderBy('nama_pcl', 'asc');
    }

    public static function table(Table $table): Table
    {
        $batasHonor = self::getBatasHonor();

        return $table
            ->columns([

                TextColumn::make('no')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->sortable()
                    ->badge()
                    ->color(function (string $state): string {
                        return match ($state) {
                            'Januari',
                            'Februari',
                            'Maret'
                                => 'warning',

                            'April',
                            'Mei',
                            'Juni'
                                => 'success',

                            'Juli',
                            'Agustus',
                            'September'
                                => 'info', // Membuat warna Juli, Agustus, September menjadi Biru (Info)

                            'Oktober',
                            'November',
                            'Desember'
                                => 'danger',

                            default => 'gray',
                        };
                    }),

                TextColumn::make('nama_pcl')
                    ->label('Nama Mitra')
                    ->url(function ($record) {
                        return MonitoringSurveyResource::getUrl('index', [
                            'activeTab' => strtolower($record->bulan),
                            'tableFilters' => [
                                'bulan' => [
                                    'value' => $record->bulan,
                                ],
                                'nama_pcl' => [
                                    'value' => $record->nama_pcl,
                                ],
                            ],
                        ]);
                    })
                    ->tooltip('Lihat Detail Data')
                    ->weight('medium')
                    ->extraCellAttributes([
                        'class' => 'monitoring-honor-mitra-cell',
                    ])
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jumlah_kegiatan')
                    ->label('Jumlah Kegiatan / Survei')
                    ->alignCenter(),

                TextColumn::make('total_beban')
                    ->label('Total Beban')
                    ->badge()
                    ->alignCenter()
                    ->color(function ($record) use ($batasHonor) {
                        return $record->total_honor >= $batasHonor ? 'danger' : 'success';
                    }),

                TextColumn::make('total_honor')
                    ->label('Total Honor')
                    ->money('IDR', locale: 'id')
                    ->color(function ($record) use ($batasHonor) {
                        return $record->total_honor >= $batasHonor ? 'danger' : null;
                    })
                    ->weight(function ($record) use ($batasHonor) {
                        return $record->total_honor >= $batasHonor ? FontWeight::Bold : null;
                    })
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) =>
                        $record->total_honor >= $batasHonor
                            ? 'Melebihi Batas'
                            : 'Aman'
                    )
                    ->colors([
                        'success' => 'Aman',
                        'danger' => 'Melebihi Batas',
                    ]),

            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonitoringHonors::route('/'),
        ];
    }
}