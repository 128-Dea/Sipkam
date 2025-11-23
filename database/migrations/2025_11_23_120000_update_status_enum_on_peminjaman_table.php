<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan status "booking" agar peminjaman bisa menunggu scan QR lebih dulu.
        DB::statement("
            ALTER TABLE peminjaman
            MODIFY COLUMN status ENUM('booking', 'berlangsung', 'selesai', 'dibatalkan')
            NOT NULL DEFAULT 'booking'
        ");
    }

    public function down(): void
    {
        // Kembalikan ke enum semula tanpa status booking.
        DB::statement("
            ALTER TABLE peminjaman
            MODIFY COLUMN status ENUM('berlangsung', 'selesai', 'dibatalkan')
            NOT NULL DEFAULT 'berlangsung'
        ");
    }
};
