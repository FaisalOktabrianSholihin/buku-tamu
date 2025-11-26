<?php

// namespace App\Filament\Pages;

// use Filament\Pages\Page;

// class ScanQrCode extends Page
// {
//     protected string $view = 'filament.pages.scan-qr-code';
// }



namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Tamu;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ScanQrCode extends Page
{
    // Icon di Sidebar
    // protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    // Judul Menu
    protected static ?string $navigationLabel = 'Scan QR Tamu';

    // Judul di Halaman
    protected static ?string $title = 'Validasi Pos Satpam';

    // File View
    protected string $view = 'filament.pages.scan-qr-code';

    // Variable untuk menampung data (State)
    public $qr_code;
    public $tamu_id;
    public $nama;
    public $nopol_kendaraan;
    public $jumlah_tamu;
    public $keperluan;
    public $is_found = false; // Status apakah QR ketemu

    // 1. Fungsi ini dipanggil oleh JavaScript saat scan berhasil
    public function cekQr($code)
    {
        $this->qr_code = $code;
        $tamu = Tamu::where('qr_code', $code)->first();

        if ($tamu) {
            // Isi form dengan data tamu
            $this->tamu_id = $tamu->id;
            $this->nama = $tamu->nama;
            $this->nopol_kendaraan = $tamu->nopol_kendaraan;
            $this->jumlah_tamu = $tamu->jumlah_tamu;
            $this->keperluan = $tamu->keperluan;
            $this->is_found = true;

            Notification::make()->success()->title('Data Tamu Ditemukan!')->send();
        } else {
            $this->is_found = false;
            Notification::make()->danger()->title('QR Code Tidak Terdaftar!')->send();
        }
    }

    // 2. Fungsi Validasi / Simpan Data
    public function simpanValidasi()
    {
        $tamu = Tamu::find($this->tamu_id);

        if ($tamu) {
            $tamu->update([
                'nama' => $this->nama,
                'nopol_kendaraan' => $this->nopol_kendaraan,
                'jumlah_tamu' => $this->jumlah_tamu,
                'keperluan' => $this->keperluan,
                // 'status' => 'Check-In', // Aktifkan jika ada kolom status
            ]);

            Notification::make()->success()->title('Tamu Berhasil Divalidasi Masuk')->send();

            $this->resetForm(); // Kembali ke mode scan
        }
    }

    public function resetForm()
    {
        $this->reset([
            'tamu_id',
            'nama',
            'nopol_kendaraan',
            'jumlah_tamu',
            'keperluan',
            'is_found'
        ]);

        $this->dispatch('form-reset');
    }
}
