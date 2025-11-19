<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Denda;
use App\Models\Keluhan;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Pengguna;

class PetugasController extends Controller
{
    public function dashboard()
    {
        $totalBarang = Barang::count();
        $barangTersedia = Barang::where('status', 'tersedia')->count();
        $peminjamanAktif = Peminjaman::where('status', 'berlangsung')->count();
        $dendaBelumDibayar = Denda::where('status_pembayaran', 'belum')->sum('total_denda');

        $totalPengguna = Pengguna::count();
        $totalKeluhan = Keluhan::count();
        $pengembalianHariIni = Pengembalian::whereDate('waktu_pengembalian', now())->count();
        // Belum ada kolom status di tabel notifikasi, jadi gunakan total notifikasi sebagai indikator.
        $notifikasiBelumDibaca = Notifikasi::count();

        return view('petugas.dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'peminjamanAktif',
            'dendaBelumDibayar',
            'totalPengguna',
            'totalKeluhan',
            'pengembalianHariIni',
            'notifikasiBelumDibaca'
        ));
    }
}
