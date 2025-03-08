<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Pengguna';
    protected static ?int $navigationSort = 4;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Pengguna')
                    ->required(),
                Forms\Components\TextInput::make('username')
                    ->label('Usename/NISN')
                    ->visible(Auth::user()->is_admin === 'Administrator')
                    ->required(fn($record) => $record === null)
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('email')
                    ->label('Alamat Email')
                    ->helperText('email harus berdomain @mtsn1pandeglang.sch.id')
                    ->email()
                    ->rule(fn($record) => $record === null ? 'unique:users,email' : 'unique:users,email,' . $record->id)
                    ->dehydrateStateUsing(fn($state) => $state ? $state : null)
                    ->disabledOn('edit')
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn($record) => $record === null) // Required only on create
                    ->dehydrateStateUsing(fn($state, $record) => $state ? bcrypt($state) : $record->password),
                Forms\Components\Select::make('is_active')
                    ->label('Status Pengguna')
                    ->options([
                        '0' => 'Nonaktif',
                        '1' => 'Aktif',
                    ])
                    ->required(),
                Forms\Components\Select::make('is_admin')
                    ->label('Peran Pengguna')
                    ->options([
                        'Siswa' => 'Siswa',
                        'Administrator' => 'Administrator',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        if (Auth::check()) {
            $user = Auth::user();
            $siswa = Siswa::where('nisn', $user->username)->first();
            if ($siswa && $user->is_active || $user->is_admin === 'Administrator') {
                return $table
                    ->columns([
                        Tables\Columns\TextColumn::make('name')
                            ->label('Nama Lengkap')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('email')
                            ->label('Email')
                            ->searchable(),
                        Tables\Columns\IconColumn::make('is_active')
                            ->label('Status Pengguna'),
                        Tables\Columns\TextColumn::make('is_admin')
                            ->label('Peran Pengguna')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Administrator' => 'success',
                                default => 'gray',
                            }),
                        Tables\Columns\TextColumn::make('email_verified_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                        Tables\Columns\TextColumn::make('deleted_at')
                            ->dateTime()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
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
                        Tables\Filters\TrashedFilter::make()
                            ->visible(Auth::user()->is_admin === 'Administrator'),
                    ])
                    ->actions([
                        ActionGroup::make([
                            // Tables\Actions\ViewAction::make(),
                            Tables\Actions\EditAction::make()
                                ->visible(Auth::user()->is_admin === 'Administrator'),
                        ])
                    ])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        if (Auth::check()) {
            $user = Auth::user();
            $siswa = Siswa::where('nisn', $user->username)->first();
            if ($siswa && $user->is_active || $user->is_admin === 'Administrator') {
                return [
                    'index' => Pages\ListUsers::route('/'),
                ];
            }
        }
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
