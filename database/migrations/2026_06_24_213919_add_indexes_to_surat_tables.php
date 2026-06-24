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
        $tables = [
            'surat_pkl', 'surat_aktif', 'surat_lulus', 'surat_observasi',
            'surat_penelitian', 'surat_rekomendasi'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->index('nim');
                    $table->index('status');
                    $table->index('akademik_id');
                });
            }
        }

        if (Schema::hasTable('history_pengajuan')) {
            Schema::table('history_pengajuan', function (Blueprint $table) {
                $table->index('nim');
                $table->index('status');
                $table->index('tabel');
                $table->index('id_tabel_surat');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'surat_pkl', 'surat_aktif', 'surat_lulus', 'surat_observasi',
            'surat_penelitian', 'surat_rekomendasi'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropIndex(['nim']);
                    $table->dropIndex(['status']);
                    $table->dropIndex(['akademik_id']);
                });
            }
        }

        if (Schema::hasTable('history_pengajuan')) {
            Schema::table('history_pengajuan', function (Blueprint $table) {
                $table->dropIndex(['nim']);
                $table->dropIndex(['status']);
                $table->dropIndex(['tabel']);
                $table->dropIndex(['id_tabel_surat']);
            });
        }
    }
};
