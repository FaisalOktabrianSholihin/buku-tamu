<x-filament-panels::page>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <x-filament::section>
            <div class="text-center font-bold mb-4">Kamera Scanner</div>

            {{-- Area Kamera --}}
            <div id="reader" style="width: 100%; border-radius: 8px; overflow: hidden;"></div>

            {{-- Tombol Reset Manual --}}
            <div class="mt-4 text-center">
                <x-filament::button color="gray" wire:click="resetForm" onclick="resumeScanner()">
                    Scan Ulang
                </x-filament::button>
            </div>
        </x-filament::section>


        {{-- Hanya muncul jika $is_found = true --}}
        @if ($is_found)
            <x-filament::section>
                <div class="font-bold text-xl mb-4 border-b pb-2">Data Tamu</div>

                <form wire:submit="simpanValidasi" class="space-y-4">

                    <div>
                        <label class="font-bold text-sm">Nama Tamu</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="nama" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label class="font-bold text-sm">Plat Nomor</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="nopol_kendaraan" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label class="font-bold text-sm">Jumlah Tamu</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="number" wire:model="jumlah_tamu" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label class="font-bold text-sm">Keperluan</label>
                        <textarea wire:model="keperluan" class="w-full border-gray-300 rounded-lg shadow-sm" rows="3"></textarea>
                    </div>

                    <div class="pt-4">
                        <x-filament::button type="submit" color="success" class="w-full">
                            ✅ VALIDASI & IZINKAN MASUK
                        </x-filament::button>
                    </div>

                    {{-- Info Petugas --}}
                    <div class="mt-4 p-3 bg-gray-100 rounded text-xs text-gray-500">
                        Petugas: {{ Auth::user()->name }}
                    </div>

                </form>
            </x-filament::section>
        @else
            <x-filament::section>
                <div class="text-center text-gray-400 py-10">
                    <p class="text-lg">Silakan scan QR Code tamu...</p>
                    <p class="text-sm">Data akan muncul di sini.</p>
                </div>
            </x-filament::section>
        @endif

    </div>

    {{-- LOGIKA JAVASCRIPT SCANNER --}}
    <script>
        let html5QrcodeScanner;

        document.addEventListener('DOMContentLoaded', function() {
            html5QrcodeScanner = new Html5Qrcode("reader");
            startScanner();
        });

        function startScanner() {
            html5QrcodeScanner.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                (decodedText, decodedResult) => {
                    // SUKSES SCAN
                    console.log(`Scan result: ${decodedText}`);

                    // Panggil fungsi PHP 'cekQr' menggunakan Livewire Magic ($wire)
                    @this.cekQr(decodedText);

                    // Matikan scanner sementara
                    html5QrcodeScanner.pause();
                },
                (errorMessage) => {
                    // Error scan biasa (karena belum nemu QR), abaikan saja
                }
            ).catch(err => {
                console.log("Error starting scanner", err);
            });
        }

        // Fungsi untuk menyalakan scanner lagi setelah reset
        function resumeScanner() {
            try {
                html5QrcodeScanner.resume();
            } catch (e) {
                // Jika scanner stop total, start ulang
                startScanner();
            }
        }

        // Listener: Jika PHP me-reset form, nyalakan scanner lagi
        document.addEventListener('livewire:initialized', () => {
            @this.on('form-reset', (event) => {
                resumeScanner();
            });
        });
    </script>

</x-filament-panels::page>
