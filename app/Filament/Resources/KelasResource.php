<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TahunPelajaran;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\KelasResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\KelasResource\RelationManagers;
use IbrahimBougaoua\FilaProgress\Tables\Columns\ProgressBar;
use IbrahimBougaoua\FilaProgress\Tables\Columns\CircleProgress;
use App\Filament\Resources\KelasResource\RelationManagers\SiswasRelationManager;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $label = 'Rombel/Kelas';
    protected static ?int $navigationSort = 2;
    public static function getNavigationBadge(): ?string
    {
        $tahunAktif = TahunPelajaran::where('is_active', true)->first();

        if ($tahunAktif) {
            $total_kelas = static::getModel()::where('tahun_pelajaran_id', $tahunAktif->id)->count();
            return $total_kelas;
        }
        return null;
    }
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Kelas')
                    ->required(),
                Forms\Components\Select::make('tingkat')
                    ->label('Tingkat')
                    ->options([
                        'VII' => 'VII',
                        'VIII' => 'VIII',
                        'IX' => 'IX',
                    ])
                    ->default('IX')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('tahun_pelajaran_id')
                    ->label('Tahun Pelajaran')
                    ->relationship('tahunPelajaran', 'nama')
                    ->native(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        if (Auth::check()) {
            $user = Auth::user();
            $siswa = Siswa::where('nisn', $user->username)->first();
            if (($siswa && $user->is_active) || $user->is_admin === 'Administrator') {
                return $table
                    ->columns([
                        Tables\Columns\TextColumn::make('nama')
                            ->label('Kelas')
                            ->sortable(),
                        Tables\Columns\TextColumn::make('tingkat')
                            ->label('Tingkat'),
                        Tables\Columns\TextColumn::make('tahunPelajaran.nama')
                            ->label('Tahun Pelajaran'),
                        Tables\Columns\TextColumn::make('total_pria')
                            ->label('Total Pria')
                            ->getStateUsing(function ($record) {
                                return $record->siswa()->where('jenis_kelamin', 'Laki-laki')->count();
                            })
                            ->badge(),

                        Tables\Columns\TextColumn::make('total_wanita')
                            ->label('Total Wanita')
                            ->getStateUsing(function ($record) {
                                return $record->siswa()->where('jenis_kelamin', 'Perempuan')->count();
                            })
                            ->badge(),

                        Tables\Columns\TextColumn::make('total_siswa')
                            ->label('Total Siswa')
                            ->getStateUsing(function ($record) {
                                return $record->siswa()->count();
                            })
                            ->badge(),


                        Tables\Columns\TextColumn::make('created_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('updated_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                    ])
                    ->filters([
                        SelectFilter::make('tahun_pelajaran_id')
                            ->label('Tahun Pelajaran')
                            ->relationship('tahunPelajaran', 'nama'),
                        SelectFilter::make('tingkat')
                            ->label('Tingkat')
                            ->options([
                                'VII' => 'VII',
                                'VIII' => 'VIII',
                                'IX' => 'IX',
                            ]),
                    ])
                    ->actions([
                        ActionGroup::make([
                            Tables\Actions\ViewAction::make(),
                            Tables\Actions\EditAction::make(),
                            Tables\Actions\DeleteAction::make()
                                ->visible(Siswa::count() < 0)
                        ])
                            ->visible(Auth::user()->is_admin === 'Administrator')
                    ], position: ActionsPosition::BeforeColumns)
                    ->bulkActions([
                        Tables\Actions\BulkActionGroup::make([
                            Tables\Actions\DeleteBulkAction::make(),
                            Tables\Actions\ForceDeleteBulkAction::make(),
                            Tables\Actions\RestoreBulkAction::make(),
                        ])
                            ->visible(Auth::user()->is_admin === 'Administrator' && Siswa::count() < 0),
                    ]);
            }
            return $table->columns([]);
        }
    }

    public static function getRelations(): array
    {
        return [
            SiswasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        if (Auth::check()) {
            $user = Auth::user();
            $siswa = Siswa::where('nisn', $user->username)->first();
            if ($siswa && $user->is_active || $user->is_admin === 'Administrator') {
                return [
                    'index' => Pages\ListKelas::route('/'),
                    'view' => Pages\ViewKelas::route('/{record}'),
                ];
            }
        }
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'view' => Pages\ViewKelas::route('/{record}'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
