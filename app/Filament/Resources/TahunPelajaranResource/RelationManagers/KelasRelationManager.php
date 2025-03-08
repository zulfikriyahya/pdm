<?php

namespace App\Filament\Resources\TahunPelajaranResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class KelasRelationManager extends RelationManager
{
    protected static string $relationship = 'kelas';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        if (Auth::check()) {
            $user = Auth::user();
            $siswa = Siswa::where('nisn', $user->username)->first();
            if ($siswa && $user->is_active || $user->is_admin === 'Administrator') {
                return $table
                    ->columns([
                        Tables\Columns\TextColumn::make('nama')
                            ->label('Kelas')
                            ->sortable(),
                        Tables\Columns\TextColumn::make('tingkat')
                            ->label('Tingkat'),
                        Tables\Columns\TextColumn::make('tahunPelajaran.nama')
                            ->label('Tahun Pelajaran'),
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
                            // Tables\Actions\ViewAction::make(),
                            Tables\Actions\EditAction::make(),
                            Tables\Actions\DeleteAction::make()
                        ])
                            ->visible(Auth::user()->is_admin === 'Administrator')
                    ], position: ActionsPosition::BeforeColumns)
                    ->bulkActions([
                        Tables\Actions\BulkActionGroup::make([
                            Tables\Actions\DeleteBulkAction::make(),
                            Tables\Actions\ForceDeleteBulkAction::make(),
                            Tables\Actions\RestoreBulkAction::make(),
                        ])
                            ->visible(Auth::user()->is_admin === 'Administrator'),
                    ]);
            }
            return $table->columns([]);
        }
    }
}
