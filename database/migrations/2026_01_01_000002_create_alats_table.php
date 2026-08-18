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
        Schema::create('alats', function (Blueprint $table) {
            $table->id('id_alat');
            $table->string('kode_alat', 50)->unique();
            $table->string('nama_alat', 150);
            $table->unsignedBigInteger('id_kategori');
            $table->integer('stok')->default(0);
            $table->enum('kondisi', ['Baik', 'Perlu Perbaikan', 'Rusak'])->default('Baik');
            $table->string('foto', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategoris')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};
