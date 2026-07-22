<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penggajian_id')->constrained('penggajian')->onDelete('cascade');
            $table->foreignId('komponen_gaji_id')->nullable()->constrained('komponen_gaji');
            $table->string('nama_komponen', 100);
            $table->enum('tipe', ['penghasilan', 'potongan']);
            $table->decimal('nilai', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penggajian');
    }
};
