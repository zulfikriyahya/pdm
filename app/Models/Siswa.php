<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nama_ayah',
        'nama_ibu',
        'kelas_id',
        'tahun_pelajaran_id',
        'status_verval',
        'file_foto',
        'file_kk',
        'file_ijazah',
        'nomor_telepon',
        'email',
        'password',
        'qr_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tanggal_lahir' => 'date',
        'status_verval' => 'boolean',
        'kelas_id' => 'integer',
        'tahun_pelajaran_id' => 'integer',
        'password' => 'hashed',
        'nisn' => 'string'
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
    public function tahunPelajaran(): BelongsTo
    {
        return $this->belongsTo(TahunPelajaran::class);
    }
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
