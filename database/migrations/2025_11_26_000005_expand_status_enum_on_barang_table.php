<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perlu DB::statement karena kolom enum sudah ada.
     * Tambahkan status yang dipakai di kode: hilang, nonaktif, habis, dalam_service.
     */
    public function up(): void
    {
        if (!Schema::hasTable('barang')) {
            return;
        }

        DB::statement("
            ALTER TABLE barang
            MODIFY status ENUM(
                'tersedia',
                'dipinjam',
                'rusak',
                'dalam_service',
                'hilang',
                'nonaktif',
                'habis'
            ) DEFAULT 'tersedia'
        ");
    }

    public function down(): void
    {
        if (!Schema::hasTable('barang')) {
            return;
        }

        DB::statement("
            ALTER TABLE barang
            MODIFY status ENUM(
                'tersedia',
                'dipinjam',
                'rusak',
                'dalam_service'
            ) DEFAULT 'tersedia'
        ");
    }
};
