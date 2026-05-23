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
        Schema::create('mahasiswa_eligible_lulus', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->unsignedBigInteger('fakultas_id');
            $table->unsignedBigInteger('akademik_id');
            $table->unsignedBigInteger('added_by');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['nim', 'akademik_id']);

            $table->index('fakultas_id');
            $table->index('akademik_id');
            $table->index('added_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_eligible_lulus');
    }
};
