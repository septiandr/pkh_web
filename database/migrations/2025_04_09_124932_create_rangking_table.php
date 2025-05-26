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
        Schema::create('rangking', function (Blueprint $table) {
            $table->id('id_rangking');
            $table->string('keterangan', 50)->nullable();
            $table->unsignedBigInteger('id_alternatif');
            $table->decimal('total_nilai', 8, 2)->default(0.00);
            $table->timestamps();
    
            $table->foreign('id_alternatif')->references('id_alternatif')->on('alternatif')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rangking');
    }
};
