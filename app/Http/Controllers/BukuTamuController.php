<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\TamuPengiring;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BukuTamuController extends Controller
{
    public function index()
    {
        return view('bukutamu.form');
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'tanggal' => 'required|date',
            'divisi' => 'required',
            'penerima_tamu' => 'required|string',
            'keperluan' => 'required|string',
            'nama' => 'required|string',
            'jabatan' => 'required|string',
            'instansi' => 'required|string',
            'jumlah_tamu' => 'required|integer|min:1',
        ]);

        // --- MULAI LOGIKA GENERATE QR CODE ---

        // 1. Ambil waktu sekarang
        $now = Carbon::now();

        // 2. Buat format tanggal: 2 angka tahun, 2 bulan, 2 tanggal (cth: 251126)
        $dateCode = $now->format('ymd');

        // 3. Kode Statis
        $staticCode = 'M27';

        // 4. Hitung jumlah tamu yang dibuat HARI INI untuk menentukan urutan
        // Kita gunakan created_at agar reset setiap hari baru
        $countToday = Tamu::whereDate('created_at', $now->toDateString())->count();

        // 5. Tambahkan 1 untuk tamu saat ini dan format menjadi 4 digit (0001)
        $sequence = sprintf("%04d", $countToday + 1);

        // 6. Gabungkan menjadi format: 251126.M27.0001
        $generatedQrCode = "{$dateCode}.{$staticCode}.{$sequence}";

        // --- SELESAI LOGIKA GENERATE QR CODE ---

        $tamu = Tamu::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'instansi' => $request->instansi,
            'no_hp' => $request->no_hp,
            'jumlah_tamu' => $request->jumlah_tamu,
            'penerima_tamu' => $request->penerima_tamu,
            'nopol_kendaraan' => $request->nopol_kendaraan,
            'bidang_usaha' => $request->bidang_usaha,
            'status_tamu' => $request->status_tamu,
            'id_divisi' => $request->divisi,
            'id_status' => 1,
            'keperluan' => $request->keperluan,
            'qr_code' => $generatedQrCode,
        ]);

        if ($request->has('nama_pengiring')) {
            $list_nama = $request->input('nama_pengiring', []);
            $list_jabatan = $request->input('jabatan_pengiring', []);
            if (is_array($list_nama) && count($list_nama) > 0) {

                foreach ($list_nama as $key => $nama) {
                    // Hanya simpan jika nama tidak kosong
                    if (!empty($nama)) {

                        TamuPengiring::create([
                            // Sesuai field di Model Anda:
                            'id_tamu' => $tamu->id,  // ID dari tamu utama yg baru dibuat
                            'nama'    => $nama,
                            'jabatan' => $list_jabatan[$key] ?? '-', // Pakai strip jika jabatan kosong
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Data tamu berhasil disimpan. Kode QR: ' . $generatedQrCode);
    }
}
