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
        if (!Schema::hasColumn('surat_pkl', 'anggota_kelompok')) {
            Schema::table('surat_pkl', function (Blueprint $table) {
                $table->longText('anggota_kelompok')->nullable()->after('nim');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('surat_pkl', 'anggota_kelompok')) {
            Schema::table('surat_pkl', function (Blueprint $table) {
                $table->dropColumn('anggota_kelompok');
            });
        }
    }
};
