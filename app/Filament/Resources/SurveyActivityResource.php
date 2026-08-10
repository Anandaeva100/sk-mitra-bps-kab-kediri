<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyActivityResource\Pages;
use App\Models\SurveyActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class SurveyActivityResource extends Resource
{
    protected static ?string $model = SurveyActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Daftar Kegiatan / Survei';

    protected static ?string $modelLabel = 'Daftar Kegiatan / Survei';

    protected static ?string $pluralModelLabel = 'Daftar Kegiatan / Survei';

    // Kelompokkan ke INPUT DATA
    protected static ?string $navigationGroup = 'MASTER DATA';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Informasi Kegiatan / Survei')
                    ->schema([

                        TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        TextInput::make('tahun')
                            ->label('Tahun')
                            ->numeric()
                            ->required()
                            ->default(date('Y')),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Aktif' => 'Aktif',
                                'Tidak Aktif' => 'Tidak Aktif',
                            ])
                            ->default('Aktif')
                            ->required(),

                    ])
                    ->columns(2),

            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('nama_kegiatan', 'asc');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('no')
                    ->label('No.')
                    ->alignCenter()
                    ->rowIndex(),

                TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan / Survei')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Aktif',
                        'danger' => 'Tidak Aktif',
                    ]),

            ])
            
            ->filters([

                SelectFilter::make('tahun')
                    ->label('Tahun')
                    ->options(fn () => SurveyActivity::distinct()->orderBy('tahun', 'desc')->pluck('tahun', 'tahun')->toArray()),

            ])

            ->actions([

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

        ];
    }

    public static function getPages(): array
    {
        return [

            'index' => Pages\ListSurveyActivities::route('/'),

            'create' => Pages\CreateSurveyActivity::route('/create'),

            'edit' => Pages\EditSurveyActivity::route('/{record}/edit'),

        ];
    }
}