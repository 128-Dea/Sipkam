<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Peminjaman
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['id_barang']);
        });
        DB::statement('ALTER TABLE peminjaman MODIFY id_barang INT UNSIGNED NULL');
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        // Notifikasi
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['id_barang']);
        });
        DB::statement('ALTER TABLE notifikasi MODIFY id_barang INT UNSIGNED NULL');
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        // Service (langsung referensi barang + via keluhan->peminjaman)
        Schema::table('service', function (Blueprint $table) {
            $table->dropForeign(['id_barang']);
        });
        DB::statement('ALTER TABLE service MODIFY id_barang INT UNSIGNED NULL');
        Schema::table('service', function (Blueprint $table) {
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Kembalikan ke NOT NULL + restrict
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['id_barang']);
        });
        DB::statement('ALTER TABLE peminjaman MODIFY id_barang INT UNSIGNED NOT NULL');
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['id_barang']);
        });
        DB::statement('ALTER TABLE notifikasi MODIFY id_barang INT UNSIGNED NOT NULL');
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });

        Schema::table('service', function (Blueprint $table) {
            $table->dropForeign(['id_barang']);
        });
        DB::statement('ALTER TABLE service MODIFY id_barang INT UNSIGNED NOT NULL');
        Schema::table('service', function (Blueprint $table) {
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }
};
