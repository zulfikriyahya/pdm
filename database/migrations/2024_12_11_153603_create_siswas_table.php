<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nisn')->unique()->nullable()->index();
            $table->string('nik')->unique()->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->enum("Laki-laki", "Perempuan");
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('tahun_pelajaran_id')->constrained('tahun_pelajarans')->cascadeOnDelete();
            $table->boolean('status_verval')->nullable();
            $table->string('file_foto')->nullable();
            $table->string('file_kk')->nullable();
            $table->string('file_ijazah')->nullable();

            $table->string('nomor_telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('qr_code')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
