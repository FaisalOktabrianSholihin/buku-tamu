<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisis = [
            [
                'id' => 1,
                'nama_divisi' => 'Teknologi Informasi',
                'ka_divisi' => 'Budi Santoso',
                'ket_divisi' => '-',
            ],
            [
                'id' => 2,
                'nama_divisi' => 'Human Resource Development',
                'ka_divisi' => 'Siti Aminah',
                'ket_divisi' => '-',
            ],
            [
                'id' => 3,
                'nama_divisi' => 'Keuangan dan Akuntansi',
                'ka_divisi' => 'Rahmat Hidayat',
                'ket_divisi' => '-',
            ],
            [
                'id' => 4,
                'nama_divisi' => 'Pemasaran (Marketing)',
                'ka_divisi' => 'Diana Putri',
                'ket_divisi' => '-',
            ],
            [
                'id' => 5,
                'nama_divisi' => 'Operasional',
                'ka_divisi' => 'Eko Prasetyo',
                'ket_divisi' => '-',
            ],
            [
                'id' => 6,
                'nama_divisi' => 'Logistik dan Gudang',
                'ka_divisi' => 'Fajar Nugraha',
                'ket_divisi' => '-',
            ],
            [
                'id' => 7,
                'nama_divisi' => 'Penjualan (Sales)',
                'ka_divisi' => 'Gilang Ramadhan',
                'ket_divisi' => '-',
            ],
            [
                'id' => 8,
                'nama_divisi' => 'Legal dan Kepatuhan',
                'ka_divisi' => 'Hesti Wardani',
                'ket_divisi' => '-',
            ],
            [
                'id' => 9,
                'nama_divisi' => 'Produksi',
                'ka_divisi' => 'Iwan Kurniawan',
                'ket_divisi' => '-',
            ],
            [
                'id' => 10,
                'nama_divisi' => 'Riset dan Pengembangan (R&D)',
                'ka_divisi' => 'Joko Susilo',
                'ket_divisi' => '-',
            ],
        ];

        foreach ($divisis as $divisi) {
            Divisi::create($divisi);
        }
    }
}
