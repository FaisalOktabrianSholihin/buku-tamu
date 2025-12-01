<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Tamu;
use Illuminate\Support\Facades\Storage; // Untuk simpan file gambar
use App\Models\TandaTangan; // Load model tanda tangan
use Filament\Notifications\Notification;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

class ValidasiSatpamKeluar extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationLabel = 'Validasi Satpam (Keluar)';

    protected static ?string $title = 'Validasi Pos Satpam';

    protected string $view = 'filament.pages.validasi-satpam-keluar';

    // State Data Tamu
    public $tamu_id;
    public $nama;
    public $nopol_kendaraan;
    public $jumlah_tamu;
    public $keperluan;
    public $jabatan;
    public $no_hp;
    public $instansi;
    public $penerima_tamu;
    public $bidang_usaha;

    // Properti Tambahan (Sesuai View & DB)
    public $tanggal;
    public $divisi_id;
    public $status_tamu;
    public $agenda;
    public $keterangan;

    // Default Array Kosong (PENTING AGAR TIDAK ERROR COUNT NULL)
    public $pengirings_list = [];

    // Tanda Tangan
    public $ttd_satpam_base64;

    public $is_found = false;
    public $qr_manual = '';

    // ... (Fungsi cekQr dan cariManual sama seperti sebelumnya) ...
    public function cekQr($code)
    {
        $this->qr_manual = $code;
        $this->prosesCari($code);
    }

    public function cariManual()
    {
        if (empty($this->qr_manual)) {
            Notification::make()->warning()->title('Harap isi kode QR')->send();
            return;
        }
        $this->prosesCari($this->qr_manual);
    }

    // --- LOGIC PENCARIAN DIPERBAIKI ---
    private function prosesCari($code)
    {
        // Ambil Tamu beserta relasi pengirings
        $tamu = Tamu::where('qr_code', $code)->with('pengiring')->first();

        if ($tamu) {
            $this->tamu_id = $tamu->id;
            $this->nama = $tamu->nama;
            $this->nopol_kendaraan = $tamu->nopol_kendaraan;
            $this->jumlah_tamu = $tamu->jumlah_tamu;
            $this->keperluan = $tamu->keperluan;
            $this->jabatan = $tamu->jabatan;
            $this->no_hp = $tamu->no_hp;
            $this->instansi = $tamu->instansi;
            $this->penerima_tamu = $tamu->penerima_tamu;
            $this->bidang_usaha = $tamu->bidang_usaha;

            // Kolom tanggal & status (Sesuaikan nama kolom di DB jika beda)
            $this->tanggal = $tamu->created_at->format('Y-m-d');
            $this->divisi_id = $tamu->id_divisi;
            $this->status_tamu = $tamu->status_tamu;

            // Asumsi agenda/ket ada di tabel tamus (jika tidak ada, hapus baris ini)
            $this->agenda = $tamu->keperluan;
            $this->keterangan = '-';

            // --- AMBIL DATA DARI TABEL TAMU_PENGIRINGS ---
            // Kita ubah Collection jadi Array agar bisa di-loop di View
            if ($tamu->pengirings) {
                $this->pengirings_list = $tamu->pengirings->toArray();
            } else {
                $this->pengirings_list = [];
            }
            // ----------------------------------------------

            $this->is_found = true;
            Notification::make()->success()->title('Data Tamu Ditemukan!')->send();
        } else {
            $this->resetForm(); // Reset semua jika tidak ketemu
            Notification::make()->danger()->title('QR Code Tidak Terdaftar!')->send();
        }
    }

    // --- LOGIC SIMPAN DIPERBAIKI ---
    public function simpanAction(): Action
    {
        return Action::make('simpanAction')
            ->label('SETUJU') // Label tombol
            ->color('success')
            ->size('lg')
            ->icon('heroicon-o-check-circle')
            // ->requiresConfirmation() // Tidak perlu ini karena sudah ada ->form()
            ->modalHeading('Konfirmasi Keluar')
            ->modalDescription('Masukkan Nomor Seal/Slip/Gembok untuk memvalidasi kendaraan keluar.')
            // ->modalDescription1('Berita tanda "-" jika tamu (non-ekspedisi)')
            // ->modalDescription2('Pastikan Nopol kendaraan keluar sama dengan data QR')
            ->modalSubmitActionLabel('Simpan & Validasi')
            ->form([
                // Input No Seal muncul di dalam Pop-up
                TextInput::make('no_seal')
                    ->label('Nomor (Seal/Slip/Gembok)')
                    ->required() // Wajib diisi
                    ->placeholder('Contoh: S-12345'),
            ])
            ->action(function (array $data) {
                // $data berisi input dari form modal (no_seal)

                // 1. Cek dulu apakah Nopol diisi (dari inputan di halaman utama)
                // if (empty($this->nopol_kendaraan)) {
                //    Notification::make()->warning()->title('Nopol Kendaraan Wajib Diisi!')->send();
                //    $this->halt(); // Hentikan proses jika nopol kosong
                // }

                $tamu = Tamu::find($this->tamu_id);

                if ($tamu) {
                    $tamu->update([
                        'nama' => $this->nama,
                        'nopol_kendaraan' => $this->nopol_kendaraan, // Ambil dari wire:model di view
                        'jumlah_tamu' => $this->jumlah_tamu,
                        'no_seal' => $data['no_seal'], // <--- SIMPAN NO SEAL DARI POPUP
                        'id_visit_status' => 5, // Status Keluar
                    ]);

                    Notification::make()->success()->title('Kunjungan Selesai & No Seal Disimpan')->send();
                    $this->resetForm();
                }
            });
    }

    // public function simpanTolakValidasi()
    // {
    //     $tamu = Tamu::find($this->tamu_id);

    //     if ($tamu) {

    //         // 1. Cek Tanda Tangan
    //         if (empty($this->ttd_satpam_base64)) {
    //             Notification::make()->danger()->title('Tanda tangan satpam wajib diisi!')->send();
    //             return;
    //         }

    //         // 2. Update Data Utama
    //         $tamu->update([
    //             'nama' => $this->nama,
    //             'nopol_kendaraan' => $this->nopol_kendaraan,
    //             'jumlah_tamu' => $this->jumlah_tamu,
    //             // 'status_tamu' => 'Check In',
    //             'id_visit_status' =>  6,
    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | 3. Simpan TTD Satpam (FILE) ke STORAGE, BUKAN BASE64 KE DATABASE
    //     |--------------------------------------------------------------------------
    //     */

    //         // Hilangkan prefix base64
    //         $image = str_replace('data:image/png;base64,', '', $this->ttd_satpam_base64);
    //         $image = str_replace(' ', '+', $image);
    //         $imageData = base64_decode($image);

    //         // Nama file unik
    //         $filename = 'ttd_satpam_' . $tamu->id . '_' . time() . '.png';

    //         // Simpan ke storage/app/public/ttd
    //         Storage::disk('public')->put('ttd/' . $filename, $imageData);

    //         // Simpan PATH ke database
    //         TandaTangan::updateOrCreate(
    //             ['id_tamu' => $tamu->id],
    //             [
    //                 'ttd_satpam' => 'ttd/' . $filename,
    //                 'updated_at' => now(),
    //             ]
    //         );

    //         Notification::make()->success()->title('Validasi Tamu Di Tolak')->send();
    //         $this->resetForm();
    //     }
    // }

    public function resetForm()
    {
        $this->reset([
            'tamu_id',
            'nama',
            'nopol_kendaraan',
            'jumlah_tamu',
            'keperluan',
            'jabatan',
            'instansi',
            'penerima_tamu',
            'bidang_usaha',
            'no_hp',
            'is_found',
            'qr_manual',
            'tanggal',
            'divisi_id',
            'status_tamu',
            'agenda',
            'keterangan',
            'pengirings_list',
            // 'ttd_satpam_base64'
        ]);

        // Pastikan list di-reset ke array kosong
        $this->pengirings_list = [];

        $this->dispatch('form-reset');
    }
}
