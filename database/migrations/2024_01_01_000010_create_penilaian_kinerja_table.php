<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('penilai_id')->constrained('users');
            $table->string('periode', 20);
            $table->date('tanggal_penilaian');
            $table->decimal('nilai_disiplin', 5, 2)->default(0);
            $table->decimal('nilai_kualitas', 5, 2)->default(0);
            $table->decimal('nilai_kuantitas', 5, 2)->default(0);
            $table->decimal('nilai_tanggung_jawab', 5, 2)->default(0);
            $table->decimal('nilai_kerjasama', 5, 2)->default(0);
            $table->decimal('nilai_inisiatif', 5, 2)->default(0);
            $table->decimal('nilai_ketepatan_waktu', 5, 2)->default(0);
            $table->decimal('nilai_target', 5, 2)->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_kinerja');
    }
};
