<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringSurveyResource\Pages;
use App\Models\MonitoringSurvey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class MonitoringSurveyResource extends Resource
{
    protected static ?string $model = MonitoringSurvey::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'Data Survei';
    protected static ?string $pluralModelLabel = 'Data Survei';

    // Menyembunyikan menu bawaan "Data Survei" agar tidak double dengan list bulanan
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kegiatan')
                    ->schema([
                        Select::make('nama_kegiatan')
                            ->label('Nama Kegiatan / Survei')
                            ->options([
                                'Survei Angkatan Kerja Nasional (SAKERNAS)' => 'SAKERNAS',
                                'Survei Sosial Ekonomi Nasional (SUSENAS)' => 'SUSENAS',
                                'Survei Harga Konsumen (SHK)' => 'Survei Harga Konsumen',
                                'Pendaftaran Mitra BPS 2026' => 'Pendaftaran Mitra BPS 2026',
                            ])
                            ->required()
                            ->searchable(),
                        Select::make('bulan')
                            ->label('Bulan Kegiatan')
                            ->options([
                                'Januari' => 'Januari', 'Februari' => 'Februari', 'Maret' => 'Maret',
                                'April' => 'April', 'Mei' => 'Mei', 'Juni' => 'Juni',
                                'Juli' => 'Juli', 'Agustus' => 'Agustus', 'September' => 'September',
                                'Oktober' => 'Oktober', 'November' => 'November', 'Desember' => 'Desember',
                            ])
                            ->required(),
                    ])->columns(2),

                Section::make('Informasi Mitra & Honor')
                    ->schema([
                        TextInput::make('nama_pml')->required()->label('Nama PML'),
                        TextInput::make('nama_pcl')->required()->label('Nama PCL'),
                        
                        TextInput::make('beban_banyak')
                            ->numeric()
                            ->required()
                            ->label('Beban / Banyak')
                            ->live()
                            ->afterStateUpdated(fn ($state, Forms\Set $set, Forms\Get $get) => 
                                $set('honor_total', intval($state) * floatval($get('rate_honor')))
                            ),
                            
                        TextInput::make('rate_honor')
                            ->numeric()
                            ->required()
                            ->label('Rate Honor')
                            ->live()
                            ->afterStateUpdated(fn ($state, Forms\Set $set, Forms\Get $get) => 
                                $set('honor_total', floatval($state) * intval($get('beban_banyak')))
                            ),
                            
                        TextInput::make('honor_total')
                            ->numeric()
                            ->readOnly()
                            ->label('Honor Total (Otomatis)'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kegiatan')->searchable()->sortable()->label('Kegiatan'),
                TextColumn::make('bulan')->label('Bulan'),
                TextColumn::make('nama_pml')->searchable()->label('Nama PML'),
                TextColumn::make('nama_pcl')->searchable()->label('Nama PCL'),
                TextColumn::make('beban_banyak')->alignCenter()->label('Beban'),
                TextColumn::make('rate_honor')->money('IDR', locale: 'id')->label('Rate Honor'),
                TextColumn::make('honor_total')->money('IDR', locale: 'id')->label('Honor Total'),
            ])
            ->filters([
                SelectFilter::make('bulan')
                    ->options([
                        'Januari' => 'Januari', 'Februari' => 'Februari', 'Maret' => 'Maret',
                        'April' => 'April', 'Mei' => 'Mei', 'Juni' => 'Juni',
                        'Juli' => 'Juli', 'Agustus' => 'Agustus', 'September' => 'September',
                        'Oktober' => 'Oktober', 'November' => 'November', 'Desember' => 'Desember',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
