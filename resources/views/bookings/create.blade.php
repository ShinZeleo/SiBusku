<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">BOOKING TIKET</h1>

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Left: Data Pemesan -->
                <div class="lg:col-span-2">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">DATA PEMESAN</h2>

                        <form
                            id="bookingForm"
                            action="{{ route('bookings.store') }}"
                            method="POST"
                            class="space-y-5"
                            x-data="{ loading: false }"
                            @submit.prevent="loading = true; $el.submit();"
                        >
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                            <input type="hidden" name="selected_seats" id="selected_seats" value="{{ $selectedSeats }}">

                            <div>
                                <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                                <input
                                    type="text"
                                    id="customer_name"
                                    name="customer_name"
                                    value="{{ old('customer_name', auth()->user()->name) }}"
                                    required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                >
                                <x-error-message field="customer_name" />
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-2">No WhatsApp</label>
                                <input
                                    type="tel"
                                    id="customer_phone"
                                    name="customer_phone"
                                    value="{{ old('customer_phone', auth()->user()->phone) }}"
                                    required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                    placeholder="08xxxxxxxxxx"
                                >
                                <x-error-message field="customer_phone" />
                            </div>

                            <div>
                                <label for="seats_count" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Kursi</label>
                                <div class="flex items-center gap-3">
                                    <input
                                        type="number"
                                        id="seats_count"
                                        name="seats_count"
                                        min="1"
                                        max="{{ $trip->available_seats }}"
                                        value="{{ old('seats_count', $selectedSeats ? count(explode(',', $selectedSeats)) : 1) }}"
                                        required
                                        class="w-20 rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                    >
                                    <button
                                        type="button"
                                        onclick="openSeatModal()"
                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition"
                                    >
                                        PILIH KURSI
                                    </button>
                                </div>
                                <x-error-message field="seats_count" />
                                <x-error-message field="selected_seats" />
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-6">
                                <p class="text-sm text-blue-800">
                                    Notifikasi akan dikirim ke WhatsApp setelah booking berhasil
                                </p>
                            </div>

                            <div class="pt-4">
                                <x-loading-button type="submit" :loading-text="'Memproses booking...'">
                                    KONFIRMASI BOOKING
                                </x-loading-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right: Ringkasan Perjalanan -->
                <div>
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm sticky top-4">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">RINGKASAN PERJALANAN</h2>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Rute</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ strtoupper($trip->route->origin_city) }} → {{ strtoupper($trip->route->destination_city) }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($trip->departure_time)->format('H.i') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Bus</p>
                                <p class="text-base font-semibold text-gray-900">
                                    {{ $trip->bus->name }} ({{ $trip->bus->bus_class }})
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Harga per kursi</p>
                                <p class="text-base font-semibold text-gray-900">{{ $trip->price_formatted }}</p>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600">Total</p>
                                <p class="text-2xl font-bold text-sky-600" id="totalPrice">{{ $trip->price_formatted }}</p>
                            </div>
                        </div>
                    </div>
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
        class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        style="display: none;"
        <div
            class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-2xl bg-white"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
        >
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">PILIH KURSI</h3>
                <button onclick="closeSeatModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Left: Legend -->
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">LEGENDA</h4>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded border-2 border-gray-300 bg-white"></div>
                                <span class="text-sm text-gray-700">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded border-2 border-gray-300 bg-gray-400"></div>
                                <span class="text-sm text-gray-700">Terisi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded border-2 border-sky-600 bg-sky-600"></div>
                                <span class="text-sm text-gray-700">Dipilih</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3">INFORMASI KURSI</h4>
                        <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Kursi Dipilih:</span>
                                <span id="modalSelectedCount">0</span> / 4
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Nomor Kursi:</span>
                                <span id="modalSelectedSeats">-</span>
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Total Harga:</span>
                                <span id="modalTotalPrice">Rp 0</span>
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <button
                            onclick="recommendSeats()"
                            class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition text-sm"
                        >
                            💡 Pilih Kursi Terbaik untuk Saya
                        </button>
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
                            <!-- Seats will be loaded from API -->
                            <div class="col-span-4 text-center py-8 text-gray-500">
                                Memuat layout kursi...
                            </div>
                        </div>

                        <p class="text-center text-sm text-gray-600 mt-6">
                            Silakan pilih posisi duduk di kiri
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    onclick="closeSeatModal()"
                    class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition"
                >
                    BATAL
                </button>
                <button
                    onclick="confirmSeatSelection()"
                    id="confirmSeatBtn"
                    class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled
                >
                    GUNAKAN KURSI
                </button>
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
                // Fallback jika Alpine belum ready
                modal.style.display = 'block';
            }

            // Load seat data from API
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

            // Group by row untuk layout yang lebih baik
            const seatsByRow = {};
            seatLayout.forEach(seat => {
                const row = seat.row_index;
                if (!seatsByRow[row]) {
                    seatsByRow[row] = [];
                }
                seatsByRow[row].push(seat);
            });

            // Render seats
            Object.keys(seatsByRow).sort((a, b) => a - b).forEach(row => {
                seatsByRow[row].sort((a, b) => a.col_index - b.col_index).forEach(seat => {
                    const seatBtn = document.createElement('button');
                    seatBtn.type = 'button';
                    seatBtn.className = 'w-12 h-12 rounded border-2 transition';
                    seatBtn.textContent = seat.seat_number;
                    seatBtn.dataset.seatNumber = seat.seat_number;
                    seatBtn.onclick = () => toggleSeat(seat.seat_number);

                    if (seat.status === 'booked') {
                        seatBtn.classList.add('bg-gray-400', 'border-gray-400', 'cursor-not-allowed');
                        seatBtn.disabled = true;
                    } else {
                        seatBtn.classList.add('bg-white', 'border-gray-300', 'hover:border-sky-500');
                    }

                    if (selectedSeats.includes(seat.seat_number)) {
                        seatBtn.classList.remove('bg-white', 'border-gray-300');
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
                    // Clear current selection
                    selectedSeats = [];

                    // Select recommended seats
                    data.recommended_seats.forEach(seatNumber => {
                        if (!bookedSeats.includes(seatNumber) && selectedSeats.length < maxSeats) {
                            selectedSeats.push(seatNumber);
                        }
                    });

                    // Update UI
                    generateSeatMap();
                    updateSeatInfo();

                    // Update seats_count input
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
            if (bookedSeats.includes(seatNumber)) {
                return; // Jangan biarkan pilih kursi yang sudah dibooking
            }

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

        // Update total when seats_count changes
        document.getElementById('seats_count').addEventListener('input', updateTotalPrice);

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('seatModal');
            if (event.target == modal) {
                closeSeatModal();
            }
        }
    </script>
</x-app-layout>
