<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringHonorResource\Pages;
use App\Models\MonitoringSurvey;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    /**
     * Query Monitoring Honor
     * Mengelompokkan berdasarkan Bulan & Nama PCL, serta mengurutkan Bulan & Nama A-Z
     */
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
        return $table
            ->columns([

                TextColumn::make('no')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->sortable()
                    ->badge(),

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
            ->filters([

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