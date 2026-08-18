<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->string('kode_peminjaman', 50)->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_alat');
            $table->integer('jumlah_pinjam')->default(1);
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali_rencana');
            $table->date('tgl_kembali_aktual')->nullable();
            $table->enum('status', [
                'Menunggu Konfirmasi',
                'Disetujui',
                'Ditolak',
                'Sedang Dipinjam',
                'Dikembalikan'
            ])->default('Menunggu Konfirmasi');
            $table->text('catatan_peminjam')->nullable();
            $table->text('catatan_petugas')->nullable();
            $table->decimal('denda', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_alat')
                  ->references('id_alat')
                  ->on('alats')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
