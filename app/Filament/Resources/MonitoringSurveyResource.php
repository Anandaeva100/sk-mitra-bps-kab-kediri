<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringSurveyResource\Pages;
use App\Models\MonitoringSurvey;
use App\Models\SurveyActivity;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;

class MonitoringSurveyResource extends Resource
{
    protected static ?string $model = MonitoringSurvey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Data Survei';

    protected static ?string $modelLabel = 'Data Survei';

    protected static ?string $pluralModelLabel = 'Data Survei';

    protected static bool $shouldRegisterNavigation = true;

    /**
     * Mengembalikan null agar badge angka (12) di menu Data Survei hilang
     */
    public static function getNavigationBadge(): ?string
    {
        return null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Informasi Kegiatan')
                    ->schema([

                        Select::make('nama_kegiatan')
                            ->label('Nama Kegiatan / Survei')
                            ->options(function () {
                                return SurveyActivity::where('status', 'Aktif')
                                    ->orderBy('nama_kegiatan')
                                    ->pluck('nama_kegiatan', 'nama_kegiatan');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('bulan')
                            ->label('Bulan Kegiatan')
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
                            ])
                            ->required(),

                    ])
                    ->columns(2),

                Section::make('Informasi Mitra & Honor')
                    ->schema([

                        TextInput::make('nama_pml')
                            ->label('Nama PML')
                            ->required(),

                        TextInput::make('nama_pcl')
                            ->label('Nama PCL')
                            ->required(),

                        TextInput::make('beban_banyak')
                            ->label('Beban / Banyak')
                            ->numeric()
                            ->required()
                            ->live(),

                        TextInput::make('rate_honor')
                            ->label('Rate Honor (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->live(),

                        TextInput::make('honor_total')
                            ->label('Honor Total')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($get) {

                                $beban = (float) ($get('beban_banyak') ?? 0);
                                $rate = (float) ($get('rate_honor') ?? 0);

                                return 'Rp ' . number_format(
                                    $beban * $rate,
                                    0,
                                    ',',
                                    '.'
                                );

                            }),

                    ])
                    ->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->deferFilters(false)
            
            ->columns([

                Tables\Columns\TextColumn::make('no')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bulan')
                    ->label('Bulan'),

                TextColumn::make('nama_pml')
                    ->label('Nama PML')
                    ->searchable(),

                TextColumn::make('nama_pcl')
                    ->label('Nama PCL')
                    ->searchable(),

                TextColumn::make('beban_banyak')
                    ->label('Beban')
                    ->alignCenter(),

                TextColumn::make('rate_honor')
                    ->label('Rate Honor')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('honor_total')
                    ->label('Honor Total')
                    ->money('IDR', locale: 'id'),

            ])
            ->filters([

                SelectFilter::make('bulan')
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
                
                SelectFilter::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->options(function () {
                        return MonitoringSurvey::query()
                            ->distinct()
                            ->orderBy('nama_kegiatan')
                            ->pluck('nama_kegiatan', 'nama_kegiatan')
                            ->toArray();
                    }),

                SelectFilter::make('nama_pml')
                    ->label('PML')
                    ->searchable()
                    ->options(function () {
                        return MonitoringSurvey::query()
                            ->distinct()
                            ->orderBy('nama_pml')
                            ->pluck('nama_pml', 'nama_pml')
                            ->toArray();
                    }),

                SelectFilter::make('nama_pcl')
                    ->label('PCL')
                    ->searchable()
                    ->options(function () {
                        return MonitoringSurvey::query()
                            ->distinct()
                            ->orderBy('nama_pcl')
                            ->pluck('nama_pcl', 'nama_pcl')
                            ->toArray();
                    }),

            ])
            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ]);
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListMonitoringSurveys::route('/'),

            'create' => Pages\CreateMonitoringSurvey::route('/create'),

            'edit' => Pages\EditMonitoringSurvey::route('/{record}/edit'),

        ];
    }
}