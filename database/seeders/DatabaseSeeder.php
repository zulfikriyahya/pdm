<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Verifikasi;
use App\Models\TahunPelajaran;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // TahunPelajaran
        TahunPelajaran::create([
            'nama' => '2024/2025',
            'is_active' => true,
        ]);
        TahunPelajaran::create([
            'nama' => '2025/2026',
            'is_active' => false,
        ]);

        // Kelas VII
        Kelas::create([
            'nama' => 'VII A',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII B',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII C',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII D',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII E',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII F',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII G',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII H',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII I',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII J',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VII K',
            'tingkat' => 'VII',
            'tahun_pelajaran_id' => 1,
        ]);

        // Kelas VIII
        Kelas::create([
            'nama' => 'VIII A',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII B',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII C',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII D',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII E',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII F',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII G',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII H',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII I',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'VIII J',
            'tingkat' => 'VIII',
            'tahun_pelajaran_id' => 1,
        ]);

        // Kelas IX
        Kelas::create([
            'nama' => 'IX A',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX B',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX C',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX D',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX E',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX F',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX G',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX H',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX I',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
        Kelas::create([
            'nama' => 'IX J',
            'tingkat' => 'IX',
            'tahun_pelajaran_id' => 1,
        ]);
    }
}
