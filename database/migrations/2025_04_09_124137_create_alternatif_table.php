<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('alternatif', function (Blueprint $table) {
            $table->id('id_alternatif');
            $table->string('nik', 16);
            $table->string('nama_lengkap', 50);
            $table->string('alamat', 50);
            $table->boolean('eligible')->default(false);
            $table->string('dokumen')->nullable();
            $table->string('pendapatan', 30);
            $table->string('luas_tanah',20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alternatif');
    }
};
