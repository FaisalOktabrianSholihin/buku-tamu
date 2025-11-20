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
        Schema::create('tamus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('instansi');
            $table->string('no_hp');
            $table->unsignedBigInteger('id_divisi')->nullable();
            $table->unsignedBigInteger('id_status')->nullable();
            $table->unsignedBigInteger('id_layanan')->nullable();
            $table->string('keperluan');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamus');
    }
};
