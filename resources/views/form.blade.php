<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Buku Tamu</title>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        fieldset {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        legend {
            font-weight: bold;
            color: #555;
            padding: 0 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="datetime-local"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        .submit-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #00620c;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #008a12;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>📝 Form Buku Tamu Perusahaan</h2>

        {{-- Notifikasi sukses kustom --}}
        @if (session('success'))
            <div id="custom-success-notification" class="custom-notification show">
                {{ session('success') }}
            </div>
        @endif

        <style>
            /* Gaya CSS Kustom */
            .custom-notification {
                padding: 15px 20px;
                background: #187e03;
                /* Warna biru modern, ganti ke hijau jika suka */
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                opacity: 0;
                /* Mulai dengan transparan */
                transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
                /* Animasi transisi */
                transform: translateY(-20px);
                /* Mulai agak di atas */
            }

            .custom-notification.show {
                opacity: 1;
                /* Tampilkan penuh saat ada class 'show' */
                transform: translateY(0);
                /* Geser ke posisi akhir */
            }
        </style>

        <script>
            // Ambil elemen notifikasi
            const customAlert = document.getElementById('custom-success-notification');

            if (customAlert) {
                // Pastikan notifikasi muncul dulu
                setTimeout(() => {
                    // Hapus class 'show' untuk memicu animasi menghilang (opacity ke 0)
                    customAlert.classList.remove('show');

                    // Hapus elemen sepenuhnya setelah animasi selesai (0.5 detik)
                    setTimeout(() => {
                        customAlert.remove();
                    }, 500); // 500ms = waktu yang sama dengan CSS transition
                }, 5000); // 5000 ms = 5 detik (waktu tunggu)
            }
        </script>


        {{-- Form --}}
        <form action="{{ route('bukutamu.store') }}" method="POST">
            @csrf

            {{-- INFORMASI KUNJUNGAN --}}
            <fieldset>
                <legend>Informasi Kunjungan</legend>

                <div class="form-group">
                    <label>Tanggal:</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required readonly>
                </div>

                <div class="form-group">
                    <label>Divisi yang Tujuan:</label>
                    <select name="divisi" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach (\App\Models\Divisi::all() as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Penerima Tamu:</label>
                    <input type="text" name="penerima_tamu" placeholder="Nama petugas yang menerima" required>
                </div>

                <div class="form-group">
                    <label>Keperluan:</label>
                    <textarea name="keperluan" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Nopol Kendaraan:</label>
                    <input type="text" name="nopol_kendaraan" placeholder="Contoh: B 1234 ABC">
                </div>
            </fieldset>

            {{-- TAMU UTAMA --}}
            <fieldset>
                <legend>Data Tamu Utama</legend>

                <div class="form-group">
                    <label>Nama Tamu Utama:</label>
                    <input type="text" name="nama" required>
                </div>

                <div class="form-group">
                    <label>Jabatan:</label>
                    <input type="text" name="jabatan" required>
                </div>

                <div class="form-group">
                    <label>No HP:</label>
                    <input type="number" name="no_hp" required>
                </div>

                <div class="form-group">
                    <label>Nama Perusahaan:</label>
                    <input type="text" name="instansi" required>
                </div>

                <div class="form-group">
                    <label>Bidang Usaha:</label>
                    <input type="text" name="bidang_usaha">
                </div>

                <div class="form-group">
                    <label>Status:</label>
                    <select name="status_tamu" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Supplier">Supplier</option>
                        <option value="Customer/Buyer">Customer / Buyer</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah Tamu Total (Termasuk Utama):</label>
                    {{-- Tambahkan ID di sini untuk dipanggil JS --}}
                    <input type="number" id="jumlah_tamu" name="jumlah_tamu" min="1" value="1" required>
                </div>
            </fieldset>

            {{-- PENGIRING (DINAMIS) --}}
            {{-- Fieldset ini akan disembunyikan defaultnya oleh CSS, muncul jika tamu > 1 --}}
            <fieldset id="fieldset-pengiring" style="display: none;">
                <legend>Data Pengiring</legend>

                {{-- Container kosong ini akan diisi oleh JavaScript --}}
                <div id="container-pengiring"></div>
            </fieldset>

            {{-- AGENDA --}}
            <fieldset>
                <legend>Detail Waktu & Agenda</legend>

                <div class="form-group">
                    <label>Agenda:</label>
                    <textarea name="agenda" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label>Keterangan Tambahan:</label>
                    <textarea name="keterangan" rows="2"></textarea>
                </div>
            </fieldset>

            {{-- AREA TANDA TANGAN --}}
            <fieldset>
                <legend>Tanda Tangan Tamu</legend>
                <div class="form-group">
                    <p style="font-size: 0.9em; color: #666;">Silakan tanda tangan di kotak ini:</p>

                    <div style="border: 2px dashed #00620c; background: #fff; border-radius: 5px;">
                        <canvas id="signature-canvas" style="width: 100%; height: 200px; display: block;"></canvas>
                    </div>

                    <button type="button" id="clear-signature"
                        style="margin-top: 10px; background: #d9534f; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Hapus
                        Tanda Tangan</button>

                    <input type="hidden" name="ttd_tamu_base64" id="ttd_tamu_base64">
                </div>
            </fieldset>

            <button type="submit" class="submit-button">Simpan Data Tamu</button>
        </form>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputJumlah = document.getElementById('jumlah_tamu');
            const fieldsetPengiring = document.getElementById('fieldset-pengiring');
            const containerPengiring = document.getElementById('container-pengiring');

            // Fungsi untuk generate form
            function generatePengiringForms() {
                const totalTamu = parseInt(inputJumlah.value) || 1;

                // Hitung jumlah pengiring (Total - 1 Tamu Utama)
                const jumlahPengiring = totalTamu - 1;

                // Kosongkan container dulu agar tidak duplikat
                containerPengiring.innerHTML = '';

                if (jumlahPengiring > 0) {
                    // Tampilkan fieldset
                    fieldsetPengiring.style.display = 'block';

                    // Loop untuk membuat input sesuai jumlah pengiring
                    for (let i = 1; i <= jumlahPengiring; i++) {
                        const html = `
                        <div class="pengiring-item" style="margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 15px;">
                            <p style="font-weight:bold; margin-bottom:10px; color:#00620c;"># Pengiring ${i}</p>
                            <div class="form-group">
                                <label>Nama Pengiring:</label>
                                <input type="text" name="nama_pengiring[]" placeholder="Nama Pengiring ke-${i}" required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan Pengiring:</label>
                                <input type="text" name="jabatan_pengiring[]" placeholder="Jabatan Pengiring ke-${i}">
                            </div>
                        </div>
                    `;
                        // Masukkan ke dalam container
                        containerPengiring.insertAdjacentHTML('beforeend', html);
                    }
                } else {
                    // Jika tamu cuma 1, sembunyikan fieldset pengiring
                    fieldsetPengiring.style.display = 'none';
                }
            }

            // Panggil fungsi saat user mengetik/mengubah angka
            inputJumlah.addEventListener('input', generatePengiringForms);

            // Panggil sekali saat halaman dimuat (untuk handle old input jika validasi gagal)
            generatePengiringForms();

            // --- LOGIKA TANDA TANGAN ---
            const canvas = document.getElementById('signature-canvas');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)', // Transparan atau Putih
                penColor: 'rgb(0, 0, 0)'
            });

            // Fungsi Resize agar canvas tidak gepeng/pecah di HP
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear(); // Bersihkan saat resize
            }
            window.addEventListener("resize", resizeCanvas);
            resizeCanvas(); // Panggil sekali saat awal load

            // Tombol Clear
            document.getElementById('clear-signature').addEventListener('click', function() {
                signaturePad.clear();
            });

            // Saat Submit Form, pindahkan gambar ke input hidden
            document.querySelector('form').addEventListener('submit', function(e) {
                if (!signaturePad.isEmpty()) {
                    const dataURL = signaturePad.toDataURL('image/png');
                    document.getElementById('ttd_tamu_base64').value = dataURL;
                } else {
                    // Opsional: Jika tanda tangan wajib, uncomment baris ini:
                    // e.preventDefault(); alert("Mohon tanda tangan terlebih dahulu.");
                }
            });
        });
    </script>
</body>

</html>
