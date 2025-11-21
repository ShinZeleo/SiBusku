<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-6xl mx-auto px-4 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Tiket</h1>
                <p class="text-gray-600">Lengkapi data pemesan untuk melanjutkan</p>
            </div>

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Left: Data Pemesan -->
                <div class="lg:col-span-2">
                    <x-card class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Data Pemesan</h2>

                        <form
                            id="bookingForm"
                            action="{{ route('bookings.store') }}"
                            method="POST"
                            class="space-y-6"
                            x-data="{ loading: false }"
                            @submit.prevent="loading = true; $el.submit();"
                        >
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                            <input type="hidden" name="selected_seats" id="selected_seats" value="{{ $selectedSeats }}">

                            <x-form.input
                                label="Nama Lengkap"
                                name="customer_name"
                                type="text"
                                :value="old('customer_name', auth()->user()->name)"
                                :required="true"
                            />

                            <x-form.input
                                label="Nomor WhatsApp"
                                name="customer_phone"
                                type="tel"
                                :value="old('customer_phone', auth()->user()->phone)"
                                :required="true"
                                placeholder="08xxxxxxxxxx"
                                help="Notifikasi akan dikirim ke nomor ini"
                            />

                            <div>
                                <label for="seats_count" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jumlah Kursi <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    <input
                                        type="number"
                                        id="seats_count"
                                        name="seats_count"
                                        min="1"
                                        max="{{ $trip->available_seats }}"
                                        value="{{ old('seats_count', $selectedSeats ? count(explode(',', $selectedSeats)) : 1) }}"
                                        required
                                        class="w-24 rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 transition"
                                    >
                                    <x-button.secondary type="button" onclick="openSeatModal()">
                                        📍 Pilih Kursi
                                    </x-button.secondary>
                                </div>
                                <x-error-message field="seats_count" />
                                <x-error-message field="selected_seats" />
                            </div>

                            <x-alert.info>
                                <strong>Info:</strong> Notifikasi booking akan dikirim ke WhatsApp setelah proses selesai.
                            </x-alert.info>

                            <div class="pt-4 border-t border-gray-200">
                                <x-button.primary type="submit" :disabled="false" class="w-full lg:w-auto">
                                    <span x-show="!loading">Konfirmasi Booking</span>
                                    <span x-show="loading" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memproses booking...
                                    </span>
                                </x-button.primary>
                            </div>
                        </form>
                    </x-card>
                </div>

                <!-- Right: Ringkasan Perjalanan -->
                <div>
                    <x-card class="sticky top-4">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Perjalanan</h2>

                        <div class="space-y-4">
                            <div class="pb-4 border-b border-gray-200">
                                <p class="text-sm text-gray-600 mb-1">Rute</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $trip->route->origin_city }} → {{ $trip->route->destination_city }}
                                </p>
                            </div>

                            <div class="pb-4 border-b border-gray-200">
                                <p class="text-sm text-gray-600 mb-1">Tanggal & Waktu</p>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }},
                                    {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}
                                </p>
                            </div>

                            <div class="pb-4 border-b border-gray-200">
                                <p class="text-sm text-gray-600 mb-1">Bus</p>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $trip->bus->name }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $trip->bus->bus_class }}</p>
                            </div>

                            <div class="pb-4 border-b border-gray-200">
                                <p class="text-sm text-gray-600 mb-1">Harga per kursi</p>
                                <p class="text-lg font-bold text-sky-600">{{ $trip->price_formatted }}</p>
                            </div>

                            <div class="pt-2">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm font-semibold text-gray-700">Total</p>
                                    <p class="text-2xl font-bold text-sky-600" id="totalPrice">{{ $trip->price_formatted }}</p>
                                </div>
                                <p class="text-xs text-gray-500">Harga akan disesuaikan dengan jumlah kursi</p>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    <!-- Seat Selection Modal dengan Animasi -->
    <div
        id="seatModal"
        x-data="{ open: false }"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
        style="display: none;"
    >
        <div
            class="relative top-10 mx-auto p-6 border w-full max-w-5xl shadow-2xl rounded-2xl bg-white my-10"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            @click.away="open = false"
        >
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Pilih Kursi</h3>
                <button onclick="closeSeatModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Left: Legend & Info -->
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Legenda</h4>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded border-2 border-gray-300 bg-white flex items-center justify-center text-xs font-semibold"></div>
                                <span class="text-sm text-gray-700">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded border-2 border-gray-400 bg-gray-400 flex items-center justify-center text-xs font-semibold text-white"></div>
                                <span class="text-sm text-gray-700">Terisi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded border-2 border-sky-600 bg-sky-600 flex items-center justify-center text-xs font-semibold text-white"></div>
                                <span class="text-sm text-gray-700">Dipilih</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3">Informasi</h4>
                        <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Kursi Dipilih:</span>
                                <span id="modalSelectedCount" class="text-gray-900">0</span> / 4
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Nomor Kursi:</span>
                                <span id="modalSelectedSeats" class="text-gray-900">-</span>
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Total Harga:</span>
                                <span id="modalTotalPrice" class="text-sky-600 font-bold">Rp 0</span>
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <x-button.secondary type="button" onclick="recommendSeats()" class="w-full">
                            💡 Pilih Kursi Terbaik
                        </x-button.secondary>
                    </div>
                </div>

                <!-- Center: Seat Map -->
                <div class="md:col-span-2">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="text-center mb-4">
                            <div class="inline-block bg-gray-200 rounded-lg px-4 py-2">
                                <span class="text-sm font-semibold text-gray-700">DRIVER</span>
                            </div>
                        </div>

                        <!-- Seat Grid -->
                        <div id="seatMapGrid" class="grid grid-cols-4 gap-3">
                            <div class="col-span-4 text-center py-8 text-gray-500">
                                Memuat layout kursi...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-button.secondary type="button" onclick="closeSeatModal()">
                    Batal
                </x-button.secondary>
                <x-button.primary type="button" onclick="confirmSeatSelection()" id="confirmSeatBtn" disabled>
                    Gunakan Kursi
                </x-button.primary>
            </div>
        </div>
    </div>

    <script>
        let selectedSeats = [];
        const maxSeats = 4;
        const pricePerSeat = {{ $trip->price }};
        const tripId = {{ $trip->id }};
        const initialSeats = @json($selectedSeats ? explode(',', $selectedSeats) : []);
        let seatLayout = [];
        let bookedSeats = [];

        document.addEventListener('DOMContentLoaded', () => {
            if (initialSeats.length > 0) {
                selectedSeats = initialSeats;
                updateSeatInputs();
            }
            updateTotalPrice();
        });

        async function openSeatModal() {
            const modal = document.getElementById('seatModal');
            if (modal && modal.__x) {
                modal.__x.$data.open = true;
            } else {
                modal.style.display = 'block';
            }

            try {
                const response = await fetch(`/api/trips/${tripId}/seats`);
                const data = await response.json();

                seatLayout = data.layout || [];
                bookedSeats = seatLayout.filter(s => s.status === 'booked').map(s => s.seat_number);

                generateSeatMap();
                updateSeatInfo();
            } catch (error) {
                console.error('Error loading seats:', error);
                alert('Gagal memuat data kursi. Silakan refresh halaman.');
            }
        }

        function closeSeatModal() {
            const modal = document.getElementById('seatModal');
            if (modal && modal.__x) {
                modal.__x.$data.open = false;
            } else {
                modal.style.display = 'none';
            }
        }

        function generateSeatMap() {
            const seatMap = document.getElementById('seatMapGrid');
            seatMap.innerHTML = '';

            if (seatLayout.length === 0) {
                seatMap.innerHTML = '<div class="col-span-4 text-center py-8 text-gray-500">Tidak ada data kursi</div>';
                return;
            }

            const seatsByRow = {};
            seatLayout.forEach(seat => {
                const row = seat.row_index;
                if (!seatsByRow[row]) {
                    seatsByRow[row] = [];
                }
                seatsByRow[row].push(seat);
            });

            Object.keys(seatsByRow).sort((a, b) => a - b).forEach(row => {
                seatsByRow[row].sort((a, b) => a.col_index - b.col_index).forEach(seat => {
                    const seatBtn = document.createElement('button');
                    seatBtn.type = 'button';
                    seatBtn.className = 'w-12 h-12 rounded border-2 transition font-semibold text-xs';
                    seatBtn.textContent = seat.seat_number;
                    seatBtn.dataset.seatNumber = seat.seat_number;
                    seatBtn.onclick = () => toggleSeat(seat.seat_number);

                    if (seat.status === 'booked') {
                        seatBtn.classList.add('bg-gray-400', 'border-gray-400', 'cursor-not-allowed', 'text-white');
                        seatBtn.disabled = true;
                    } else {
                        seatBtn.classList.add('bg-white', 'border-gray-300', 'hover:border-sky-500', 'hover:bg-sky-50');
                    }

                    if (selectedSeats.includes(seat.seat_number)) {
                        seatBtn.classList.remove('bg-white', 'border-gray-300', 'hover:border-sky-500', 'hover:bg-sky-50');
                        seatBtn.classList.add('bg-sky-600', 'border-sky-600', 'text-white');
                    }

                    seatMap.appendChild(seatBtn);
                });
            });
        }

        async function recommendSeats() {
            const seatsCount = parseInt(document.getElementById('seats_count').value || '1', 10);

            try {
                const response = await fetch(`/api/trips/${tripId}/seats/recommend?count=${seatsCount}`);
                const data = await response.json();

                if (data.recommended_seats && data.recommended_seats.length > 0) {
                    selectedSeats = [];
                    data.recommended_seats.forEach(seatNumber => {
                        if (!bookedSeats.includes(seatNumber) && selectedSeats.length < maxSeats) {
                            selectedSeats.push(seatNumber);
                        }
                    });

                    generateSeatMap();
                    updateSeatInfo();
                    document.getElementById('seats_count').value = selectedSeats.length;
                } else {
                    alert('Tidak ada kursi yang direkomendasikan. Silakan pilih manual.');
                }
            } catch (error) {
                console.error('Error getting recommendations:', error);
                alert('Gagal mendapatkan rekomendasi kursi.');
            }
        }

        function toggleSeat(seatNumber) {
            if (bookedSeats.includes(seatNumber)) return;

            const index = selectedSeats.indexOf(seatNumber);
            if (index > -1) {
                selectedSeats.splice(index, 1);
            } else {
                if (selectedSeats.length < maxSeats) {
                    selectedSeats.push(seatNumber);
                } else {
                    alert('Maksimal ' + maxSeats + ' kursi yang dapat dipilih');
                    return;
                }
            }
            generateSeatMap();
            updateSeatInfo();
        }

        function updateSeatInfo() {
            document.getElementById('modalSelectedCount').textContent = selectedSeats.length;
            document.getElementById('modalSelectedSeats').textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : '-';

            const total = selectedSeats.length * pricePerSeat;
            document.getElementById('modalTotalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');

            const confirmBtn = document.getElementById('confirmSeatBtn');
            confirmBtn.disabled = selectedSeats.length === 0;
        }

        function confirmSeatSelection() {
            if (selectedSeats.length === 0) {
                alert('Pilih minimal 1 kursi');
                return;
            }

            updateSeatInputs();
            updateTotalPrice();
            closeSeatModal();
        }

        function updateSeatInputs() {
            document.getElementById('selected_seats').value = selectedSeats.join(',');
            document.getElementById('seats_count').value = selectedSeats.length;
        }

        function updateTotalPrice() {
            const seatsCount = parseInt(document.getElementById('seats_count').value || '0', 10);
            const total = seatsCount * pricePerSeat;
            document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('seats_count').addEventListener('input', updateTotalPrice);
    </script>
</x-app-layout>
