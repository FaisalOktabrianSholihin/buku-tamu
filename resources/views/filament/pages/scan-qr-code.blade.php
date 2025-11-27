<x-filament-panels::page>
    {{-- Load Library Signature Pad & QR Code --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="{ activeTab: 'scan' }">

        {{-- KOLOM KIRI (1/3): SCANNER / INPUT --}}
        <div class="md:col-span-1 space-y-6">
            <x-filament::section>
                {{-- Tabs Header --}}
                <div class="flex border-b border-gray-200 dark:border-gray-700 mb-4">
                    <button @click="activeTab = 'scan'"
                        :class="activeTab === 'scan' ? 'border-primary-500 text-primary-600' : 'text-gray-500'"
                        class="w-1/2 py-2 text-center border-b-2 font-medium text-sm transition-colors">
                        📷 Scan
                    </button>
                    <button @click="activeTab = 'manual'"
                        :class="activeTab === 'manual' ? 'border-primary-500 text-primary-600' : 'text-gray-500'"
                        class="w-1/2 py-2 text-center border-b-2 font-medium text-sm transition-colors">
                        ⌨️ Manual
                    </button>
                </div>

                {{-- Tab Scan --}}
                <div x-show="activeTab === 'scan'" wire:ignore>
                    <div id="reader" style="width: 100%; border-radius: 8px; overflow: hidden;"></div>
                </div>

                {{-- Tab Manual --}}
                <div x-show="activeTab === 'manual'" style="display: none;" class="space-y-3">
                    <label class="text-sm font-bold">Kode QR</label>
                    <x-filament::input type="text" wire:model="qr_manual" placeholder="cth: 251127.M27.0001"
                        wire:keydown.enter="cariManual" />
                    <x-filament::button wire:click="cariManual" class="w-full">Cari Data</x-filament::button>
                </div>

                <div class="mt-4 pt-4 border-t text-center">
                    <x-filament::button color="gray" size="xs" wire:click="resetForm">Reset
                        Form</x-filament::button>
                </div>
            </x-filament::section>
        </div>

        {{-- KOLOM KANAN (2/3): FORM DATA TAMU --}}
        <div class="md:col-span-2">
            @if ($is_found)
                <form class="space-y-6">

                    {{-- 1. INFORMASI KUNJUNGAN --}}
                    <div
                        class="p-4 bg-white rounded-lg border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                        <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200 border-b pb-2 mb-4">📂 Informasi
                            Kunjungan</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-bold text-gray-600">Tanggal</label>
                                <x-filament::input type="date" wire:model="tanggal" readonly />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">Divisi Tujuan</label>
                                <x-filament::input.wrapper>
                                    <x-filament::input.select wire:model="divisi_id" disabled>
                                        <option value="">- Divisi -</option>
                                        @foreach (\App\Models\Divisi::all() as $d)
                                            <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                                        @endforeach
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">Penerima Tamu</label>
                                <x-filament::input type="text" wire:model="penerima_tamu" readonly />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">Nopol Kendaraan</label>
                                <x-filament::input type="text" wire:model="nopol_kendaraan" />
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="text-sm font-bold text-gray-600">Keperluan</label>
                                <textarea wire:model="keperluan" rows="2" class="w-full border-gray-300 rounded-lg bg-gray-50" readonly></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- 2. DATA TAMU UTAMA --}}
                    <div
                        class="p-4 bg-white rounded-lg border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                        <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200 border-b pb-2 mb-4">👤 Data Tamu
                            Utama</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-bold text-gray-600">Nama</label>
                                <x-filament::input type="text" wire:model="nama" readonly />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">Jabatan</label>
                                <x-filament::input type="text" wire:model="jabatan" readonly />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">No HP</label>
                                <x-filament::input type="text" wire:model="no_hp" readonly />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">Instansi</label>
                                <x-filament::input type="text" wire:model="instansi" readonly />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">Bidang Usaha</label>
                                <x-filament::input type="text" wire:model="bidang_usaha" readonly />
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">Status</label>
                                <x-filament::input type="text" wire:model="status_tamu" readonly />
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label class="text-sm font-bold text-gray-600">Total Jumlah Tamu</label>
                                <x-filament::input type="number" wire:model="jumlah_tamu" />
                            </div>
                        </div>
                    </div>

                    {{-- 3. DATA PENGIRING (Looping) --}}
                    @if (count($pengirings_list) > 0)
                        <div
                            class="p-4 bg-white rounded-lg border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                            <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200 border-b pb-2 mb-4">👥 Data
                                Pengiring</h3>
                            <div class="space-y-3">
                                @foreach ($pengirings_list as $index => $p)
                                    <div class="bg-gray-50 p-3 rounded border border-dashed border-gray-300">
                                        <p class="text-xs font-bold text-green-700 mb-1"># Pengiring
                                            {{ $loop->iteration }}</p>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div><span class="text-gray-500">Nama:</span>
                                                <strong>{{ $p['nama'] }}</strong>
                                            </div>
                                            <div><span class="text-gray-500">Jabatan:</span>
                                                <strong>{{ $p['jabatan'] }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 4. AGENDA --}}
                    <div
                        class="p-4 bg-white rounded-lg border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                        <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200 border-b pb-2 mb-4">📅 Detail
                            Waktu & Agenda</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-bold text-gray-600">Agenda</label>
                                <textarea wire:model="agenda" rows="3" class="w-full border-gray-300 rounded-lg bg-gray-50" readonly></textarea>
                            </div>
                            <div>
                                <label class="text-sm font-bold text-gray-600">Keterangan Tambahan</label>
                                <textarea wire:model="keterangan" rows="2" class="w-full border-gray-300 rounded-lg bg-gray-50" readonly></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- 5. TANDA TANGAN SATPAM (Alpine.js Integration) --}}
                    <div class="p-4 bg-white rounded-lg border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700"
                        x-data="{
                            signaturePad: null,
                            init() {
                                let canvas = this.$refs.canvas;
                                this.signaturePad = new SignaturePad(canvas, {
                                    backgroundColor: 'rgba(255, 255, 255, 0)',
                                    penColor: 'rgb(0, 0, 0)'
                                });
                                this.resizeCanvas();
                                window.addEventListener('resize', () => this.resizeCanvas());
                            },
                            resizeCanvas() {
                                let canvas = this.$refs.canvas;
                                let ratio = Math.max(window.devicePixelRatio || 1, 1);
                                canvas.width = canvas.offsetWidth * ratio;
                                canvas.height = canvas.offsetHeight * ratio;
                                canvas.getContext('2d').scale(ratio, ratio);
                            },
                            clear() {
                                this.signaturePad.clear();
                                @this.set('ttd_satpam_base64', null); // Reset di Livewire
                            },
                            save() {
                                if (!this.signaturePad.isEmpty()) {
                                    // Kirim data Base64 ke properti Livewire 'ttd_satpam_base64'
                                    @this.set('ttd_satpam_base64', this.signaturePad.toDataURL());
                                }
                            }
                        }">

                        <h3 class="font-bold text-lg text-gray-700 dark:text-gray-200 border-b pb-2 mb-4">✍️ Tanda
                            Tangan Validasi (Satpam)</h3>

                        <div class="border-2 border-dashed border-green-600 rounded bg-white">
                            {{-- Canvas --}}
                            <canvas x-ref="canvas" style="width: 100%; height: 200px; display: block;"></canvas>
                        </div>

                        <div class="flex justify-between mt-2">
                            <button type="button" @click="clear()"
                                class="text-xs text-red-600 hover:text-red-800 underline">
                                Hapus / Ulangi Tanda Tangan
                            </button>
                            <p class="text-xs text-gray-400">Pastikan tanda tangan sebelum simpan.</p>
                        </div>

                        {{-- Tombol Simpan Utama --}}
                        {{-- Tombol SETUJU --}}
                        <div class="mt-6 pt-4 border-t">
                            <x-filament::button wire:click.prevent="simpanValidasi" color="success"
                                class="w-full py-3 text-lg font-bold shadow-lg" @click="save()">
                                ✅ SETUJU
                            </x-filament::button>
                        </div>

                        {{-- Tombol TOLAK --}}
                        <div class="mt-6">
                            <x-filament::button wire:click.prevent="simpanTolakValidasi" color="danger"
                                class="w-full py-3 text-lg font-bold shadow-lg" @click="save()">
                                ❌ TOLAK
                            </x-filament::button>
                        </div>

                    </div>

                </form>
            @else
                {{-- State Kosong --}}
                <div
                    class="flex flex-col items-center justify-center h-full bg-white rounded-lg border border-gray-200 p-10 text-gray-400 shadow">
                    <p class="text-lg font-semibold">Belum Ada Data</p>
                    <p class="text-sm">Scan QR Code atau input manual di panel kiri.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Script Scanner Logic (Sama seperti sebelumnya) --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            let html5QrcodeScanner;

            function startScanner() {
                if (!document.getElementById("reader")) return;
                if (html5QrcodeScanner?.getState() === 2) return;

                html5QrcodeScanner = new Html5Qrcode("reader");
                html5QrcodeScanner.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    (decodedText) => {
                        html5QrcodeScanner.pause();
                        @this.call('cekQr', decodedText);
                    }).catch(err => console.log("Camera Error", err));
            }

            startScanner();

            @this.on('form-reset', () => {
                if (html5QrcodeScanner) {
                    try {
                        html5QrcodeScanner.resume();
                    } catch (e) {
                        html5QrcodeScanner.clear().then(startScanner).catch(startScanner);
                    }
                } else {
                    startScanner();
                }
            });
        });
    </script>
</x-filament-panels::page>
