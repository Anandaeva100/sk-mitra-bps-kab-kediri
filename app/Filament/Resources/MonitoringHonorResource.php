<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringHonorResource\Pages;
use App\Models\MonitoringSurvey;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class MonitoringHonorResource extends Resource
{
    private const BATAS_HONOR = 3700000;

    protected static ?string $model = MonitoringSurvey::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Monitoring Honor';

    protected static ?string $modelLabel = 'Monitoring Honor';

    protected static ?string $pluralModelLabel = 'Monitoring Honor';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 4;

    /**
     * 1. Menampilkan badge angka di sidebar khusus mitra yang MELEBIHI BATAS HONOR
     */
    public static function getNavigationBadge(): ?string
    {
        $count = DB::table('monitoring_surveys')
            ->select('nama_pcl')
            ->groupBy('nama_pcl')
            ->havingRaw('SUM(honor_total) >= ?', [self::BATAS_HONOR])
            ->get()
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    /**
     * 2. Memberikan warna MERAH pada badge sidebar
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    /**
     * Query Monitoring Honor
     * Mengelompokkan berdasarkan Nama PCL
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select([
                DB::raw('MIN(id) as id'),
                'nama_pcl',
                DB::raw('COUNT(*) as jumlah_kegiatan'),
                DB::raw('SUM(beban_banyak) as total_beban'),
                DB::raw('SUM(honor_total) as total_honor'),
            ])
            ->groupBy('nama_pcl');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),

                TextColumn::make('nama_pcl')
                    ->label('Nama Mitra')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jumlah_kegiatan')
                    ->label('Jumlah Kegiatan')
                    ->alignCenter(),

                TextColumn::make('total_beban')
                    ->label('Total Beban')
                    ->alignCenter(),

                TextColumn::make('total_honor')
                    ->label('Total Honor')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) =>
                        $record->total_honor >= self::BATAS_HONOR
                            ? 'Melebihi Batas'
                            : 'Aman'
                    )
                    ->colors([
                        'success' => 'Aman',
                        'danger' => 'Melebihi Batas',
                    ]),

            ])

            ->defaultSort('total_honor', 'desc')

            ->filters([

                Tables\Filters\SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options([
                        'Januari' => 'Januari',
                        'Februari' => 'Februari',
                        'Maret' => 'Maret',
                        'April' => 'April',
                        'Mei' => 'Mei',
                        'Juni' => 'Juni',
                        'Juli' => 'Juli',
                        'Agustus' => 'Agustus',
                        'September' => 'September',
                        'Oktober' => 'Oktober',
                        'November' => 'November',
                        'Desember' => 'Desember',
                    ]),
                
                Tables\Filters\SelectFilter::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->options(
                        MonitoringSurvey::query()
                            ->pluck('nama_kegiatan', 'nama_kegiatan')
                            ->toArray()
                    ),
            ])

            ->actions([

            ])

            ->bulkActions([

            ]);
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