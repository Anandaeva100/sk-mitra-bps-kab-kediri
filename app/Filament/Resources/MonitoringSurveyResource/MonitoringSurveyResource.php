<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoringSurveyResource\Pages;
use App\Models\MonitoringSurvey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use App\Filament\Resources\MonitoringSurveyResource\Pages\JanuariSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\FebruariSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\MaretSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\AprilSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\MeiSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\JuniSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\JuliSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\AgustusSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\SeptemberSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\OktoberSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\NovemberSurveys;
use App\Filament\Resources\MonitoringSurveyResource\Pages\DesemberSurveys;

class MonitoringSurveyResource extends Resource
{
    protected static ?string $model = MonitoringSurvey::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Rekapan All Data';
    protected static ?string $navigationGroup = 'REKAPAN DATA';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('nama_kegiatan')
                    ->label('Nama Kegiatan / Survei')
                    ->options([
                        'Sakernas' => 'Survei Sakernas',
                        'Susenas' => 'Survei Susenas',
                        'Pemutakhiran Data' => 'Pemutakhiran Data',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('nama_pml')->label('Nama PML')->required(),
                Forms\Components\TextInput::make('nama_pcl')->label('Nama PCL')->required(),
                Forms\Components\TextInput::make('beban_banyak')->label('Beban / Banyak')->numeric()->required(),
                Forms\Components\TextInput::make('rate_honor')->label('Rate Honor')->numeric()->prefix('Rp')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Penginput'),
                Tables\Columns\TextColumn::make('bulan')->label('Bulan'),
                Tables\Columns\TextColumn::make('nama_kegiatan')->label('Kegiatan'),
                Tables\Columns\TextColumn::make('nama_pml')->label('Nama PML')->searchable(),
                Tables\Columns\TextColumn::make('nama_pcl')->label('Nama PCL')->searchable(),
                Tables\Columns\TextColumn::make('beban_banyak')->label('Beban')->alignCenter(),
                
                Tables\Columns\TextColumn::make('rate_honor')
                    ->label('Rate Honor')
                    ->numeric(0, ',', '.')
                    ->prefix('Rp '),
                    
                Tables\Columns\TextColumn::make('honor_total')
                    ->label('Honor Total')
                    ->numeric(0, ',', '.')
                    ->prefix('Rp ')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($livewire) => $livewire instanceof Pages\ListMonitoringSurveys === false),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($livewire) => $livewire instanceof Pages\ListMonitoringSurveys === false),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonitoringSurveys::route('/'),
            'create' => Pages\CreateMonitoringSurvey::route('/create'),
            'edit' => Pages\EditMonitoringSurvey::route('/{record}/edit'),
            
            'januari' => JanuariSurveys::route('/januari'),
            'februari' => FebruariSurveys::route('/februari'),
            'maret' => MaretSurveys::route('/maret'),
            'april' => AprilSurveys::route('/april'),
            'mei' => MeiSurveys::route('/mei'),
            'juni' => JuniSurveys::route('/juni'),
            'juli' => JuliSurveys::route('/juli'),
            'agustus' => AgustusSurveys::route('/agustus'),
            'september' => SeptemberSurveys::route('/september'),
            'oktober' => OktoberSurveys::route('/oktober'),
            'november' => NovemberSurveys::route('/november'),
            'desember' => DesemberSurveys::route('/desember'),
        ];
    }
}
