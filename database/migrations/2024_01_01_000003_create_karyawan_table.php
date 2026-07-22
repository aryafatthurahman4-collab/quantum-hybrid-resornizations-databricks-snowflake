<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 30)->unique();
            $table->string('nama_lengkap', 150);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('status_perkawinan', 20)->nullable();
            $table->date('tanggal_masuk');
            $table->enum('status_kepegawaian', ['tetap', 'kontrak', 'magang', 'honorer'])->default('kontrak');
            $table->foreignId('jabatan_id')->constrained('jabatan');
            $table->foreignId('satuan_kerja_id')->constrained('satuan_kerja');
            $table->string('foto')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
