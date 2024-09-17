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
        Schema::create('jawabans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lembar_jawab_id');
            $table->unsignedBigInteger('soal_id');
            $table->text('jawaban')->nullable();
            $table->float('poin');

            $table->timestamps();
            $table->foreign('lembar_jawab_id')->references('id')->on('lembar_jawabs')->onDelete('cascade');
            $table->foreign('soal_id')->references('id')->on('soals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawabans');
    }
};
