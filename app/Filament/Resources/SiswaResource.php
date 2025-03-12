<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use App\Models\TahunPelajaran;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use App\Filament\Exports\SiswaExporter;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\SiswaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;
    protected static ?string $label = 'Siswa';
    protected static ?string $recordTitleAttribute = 'nama';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    public static function getNavigationBadge(): ?string
    {
        $tahunAktif = TahunPelajaran::where('is_active', true)->first();

        if ($tahunAktif) {
            $total_siswa = static::getModel()::where('tahun_pelajaran_id', $tahunAktif->id)->count();
            $total_siswa_verval = static::getModel()::where('tahun_pelajaran_id', $tahunAktif->id)->where('status_verval', true)->count();
            return "Verval : $total_siswa_verval / $total_siswa";
        }
        return null;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Biodata Siswa')
                    ->description('Sesuaikan dengan Data Ijazah SD/MI.')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->relationship('kelas', 'nama')
                            ->disabled(Auth::user()->is_admin === 'Siswa')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Lengkap')
                            ->minLength(3)
                            ->maxLength(100)
                            ->required(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Laki-Laki' => 'Laki-Laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->minLength(3)
                            ->maxLength(100)
                            ->required(fn($record) => $record !== null),
                        Forms\Components\DatePicker::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->maxDate(now())
                            ->required(fn($record) => $record !== null),
                        Forms\Components\TextInput::make('nama_ayah')
                            ->label('Nama Ayah Kandung')
                            ->minLength(3)
                            ->maxLength(100)
                            ->required(fn($record) => $record !== null),
                        Forms\Components\TextInput::make('nama_ibu')
                            ->label('Nama Ibu Kandung')
                            ->minLength(3)
                            ->maxLength(100)
                            ->required(fn($record) => $record !== null),
                        Forms\Components\TextInput::make('nisn')
                            ->label('Nomor Induk Siswa Nasional (NISN)')
                            ->maxLength(10)
                            ->minLength(10)
                            ->unique(Siswa::class, 'nisn', ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'NISN ini sudah terdaftar. Silakan masukkan ulang NISN anda.',
                                'min_digits' => 'Masukkan minimal 10 digit. Silakan masukkan ulang NISN anda.',
                                'max_digits' => 'Masukkan maksimal 10 digit. Silakan masukkan ulang NISN anda.',
                            ])
                            ->numeric()
                            ->required(fn($record) => $record !== null),
                        Forms\Components\TextInput::make('nik')
                            ->label('Nomor Induk Kependudukan')
                            ->helperText('Sesuaikan dengan data Kartu Keluarga.')
                            ->maxLength(16)
                            ->minLength(16)
                            ->unique(Siswa::class, 'nisn', ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'NIK ini sudah terdaftar. Silakan masukkan ulang NIK anda.',
                                'min_digits' => 'Masukkan minimal 16 digit. Silakan masukkan ulang NIK anda.',
                                'max_digits' => 'Masukkan maksimal 16 digit. Silakan masukkan ulang NIK anda.',
                            ])
                            ->numeric()
                            ->required(fn($record) => $record !== null),
                        Forms\Components\TextInput::make('nomor_telepon')
                            ->label('Nomor Telepon')
                            ->helperText('Masukkan nomor telepon/whatsapp.')
                            ->maxLength(13)
                            ->minLength(10)
                            ->validationMessages([
                                'min_digits' => 'Masukkan minimal 10 digit. Silakan masukkan ulang Nomor Telepon anda.',
                                'max_digits' => 'Masukkan maksimal 13 digit. Silakan masukkan ulang Nomor Telepon anda.',
                            ])
                            ->numeric()
                            ->required(fn($record) => $record !== null)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Unggah File')
                    ->description('Ukuran maksimal unggah : 10 MB/File.')
                    ->icon('heroicon-m-photo')
                    ->iconColor('primary')
                    ->schema([
                        FileUpload::make('file_foto')
                            ->label('Foto Profile')
                            ->image()
                            ->fetchFileInformation(false)
                            ->imageEditor()
                            ->downloadable(true)
                            ->imageEditorAspectRatios([
                                null,
                                '1:1',
                                '4:3',
                                '3:4',
                            ])
                            ->directory(fn() => 'img/' . Auth::user()->username . '/foto')
                            ->maxSize(10240)
                            ->minSize(10)
                            ->required()
                            ->validationMessages([
                                'required' => 'Silakan unggah file Foto anda.',
                            ]),
                        FileUpload::make('file_kk')
                            ->label('Kartu Keluarga')
                            ->directory('img/kk')
                            ->image()
                            ->fetchFileInformation(false)
                            ->downloadable(true)
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '1:1',
                                '4:3',
                                '3:4',
                            ])
                            ->directory(fn() => 'img/' . Auth::user()->username . '/kk')
                            ->maxSize(10240)
                            ->minSize(10)
                            ->validationMessages([
                                'required' => 'Silakan unggah file Kartu Keluarga anda.',
                            ])
                            ->required(),
                        FileUpload::make('file_ijazah')
                            ->label('Foto Depan Ijazah SD/MI')
                            ->directory('img/ijazah')
                            ->fetchFileInformation(false)
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '1:1',
                                '4:3',
                                '3:4',
                            ])
                            ->directory(fn() => 'img/' . Auth::user()->username . '/ijazah')
                            ->maxSize(10240)
                            ->minSize(10)
                            ->downloadable(true)
                            ->validationMessages([
                                'required' => 'Silakan unggah file Ijazah SD/MI anda.',
                            ])
                            ->required(),
                    ])
                    ->columns(2)
                    ->visible(fn() => DB::table('siswas')->where('kelas_id', [22, 23, 24, 25, 26, 27, 28, 29, 30, 31])->exists()),

                Section::make('Verifikasi Data')
                    ->description('Harap periksa kembali data yang telah diisi!')
                    ->icon('heroicon-m-check-badge')
                    ->iconColor('primary')
                    ->schema([
                        Forms\Components\Checkbox::make('status_verval')
                            ->label('Verifikasi')
                            ->helperText(new HtmlString('<strong>Biodata yang saya kirim adalah benar dan dapat dipertanggung jawabkan!</strong><br/>Centang jika data sudah benar.'))
                            ->required(fn() => Auth::user()->is_admin !== 'Administrator'),
                    ])
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
                        Tables\Columns\IconColumn::make('status_verval')
                            ->label('Status Verval')
                            ->alignCenter()
                            ->boolean(),
                        Tables\Columns\ImageColumn::make('file_foto')
                            ->label('Foto')
                            ->circular()
                            ->alignCenter()
                            ->defaultImageUrl('/img/default.png'),
                        Tables\Columns\TextColumn::make('kelas.nama')
                            ->label('Kelas')
                            ->sortable(),
                        Tables\Columns\TextColumn::make('nama')
                            ->label('Nama Lengkap')
                            ->sortable()
                            ->searchable(),
                        Tables\Columns\TextColumn::make('nisn')
                            ->label('NISN')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('nik')
                            ->visible(Auth::user()->is_admin === 'Administrator')
                            ->label('NIK')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('nomor_telepon')
                            ->visible(Auth::user()->is_admin === 'Administrator')
                            ->label('Nomor Telepon')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->date('d-m-Y')
                            ->sortable(),
                        Tables\Columns\TextColumn::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('nama_ayah')
                            ->label('Nama Ayah Kandung')
                            ->visible(Auth::user()->is_admin === 'Administrator')
                            ->searchable(),
                        Tables\Columns\TextColumn::make('nama_ibu')
                            ->label('Nama Ibu Kandung')
                            ->visible(Auth::user()->is_admin === 'Administrator')
                            ->searchable(),

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
                        TrashedFilter::make()
                            ->visible(Auth::user()->is_admin === 'Administrator'),
                        SelectFilter::make('kelas_id')
                            ->label('Kelas')
                            ->relationship('kelas', 'nama'),
                        SelectFilter::make('status_verval')
                            ->label('Status Verifikasi')
                            ->options([
                                1 => 'Verifikasi',
                                0 => 'Belum Verifikasi'
                            ])
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
                            Tables\Actions\ExportBulkAction::make()
                                ->exporter(SiswaExporter::class),
                        ])
                            ->visible(Auth::user()->is_admin === 'Administrator'),
                    ]);
            }
            return $table
                ->columns([]);
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
                    'index' => Pages\ListSiswas::route('/'),
                ];
            }
        }
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'view' => Pages\ViewSiswa::route('/{record}'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }



    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes(
                [
                    SoftDeletingScope::class,
                ]
            );
    }
}
