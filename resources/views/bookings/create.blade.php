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
                                        data-max-seats="{{ $trip->available_seats }}"
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
            class="relative top-4 mx-auto p-4 lg:p-5 border w-full max-w-4xl shadow-2xl rounded-2xl bg-white my-4 lg:my-6"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            @click.away="open = false"
        >
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900">Pilih Kursi</h3>
                <button onclick="closeSeatModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-1 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex flex-col lg:flex-row gap-4 items-start">
                <!-- Left: Legend & Info -->
                <div class="w-full lg:w-64 space-y-4 flex-shrink-0">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3 text-base">Legenda</h4>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded border-2 border-gray-300 bg-white flex items-center justify-center text-xs font-semibold shadow-sm"></div>
                                <span class="text-sm text-gray-700 font-medium">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded border-2 border-gray-400 bg-gray-400 flex items-center justify-center text-xs font-semibold text-white shadow-sm"></div>
                                <span class="text-sm text-gray-700 font-medium">Terisi</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded border-2 border-sky-600 bg-sky-600 flex items-center justify-center text-xs font-semibold text-white shadow-sm"></div>
                                <span class="text-sm text-gray-700 font-medium">Dipilih</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-900 mb-3 text-base">Informasi</h4>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-3 space-y-2 border border-gray-200 shadow-sm">
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">Kursi Dipilih:</span>
                                <span id="modalSelectedCount" class="text-gray-900 font-bold ml-1">0</span>
                                <span class="text-gray-500">/ <span id="modalMaxSeats">{{ $trip->available_seats }}</span></span>
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">Nomor Kursi:</span>
                                <span id="modalSelectedSeats" class="text-gray-900 font-medium ml-1">-</span>
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">Total Harga:</span>
                                <span id="modalTotalPrice" class="text-sky-600 font-bold text-base ml-1">Rp 0</span>
                            </p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <x-button.secondary type="button" onclick="recommendSeats()" class="w-full">
                            <span class="mr-2">💡</span>
                            Pilih Kursi Terbaik
                        </x-button.secondary>
                    </div>
                </div>

                <!-- Seat Map -->
                <div class="flex-1 flex justify-center items-center min-w-0">
                    <div class="bg-gradient-to-b from-slate-50 to-slate-100 rounded-xl p-3 shadow-lg border border-slate-300 relative overflow-hidden w-full">
                        <!-- Seat Grid with Pintu Masuk, Driver, Seats, and Pintu Keluar -->
                        <div id="seatMapGrid" class="relative z-10 mx-auto" style="line-height: 0; width: fit-content; transform-origin: center;">
                            <div class="text-center py-8 text-gray-500 text-sm">
                                Memuat layout kursi...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                <x-button.secondary type="button" onclick="closeSeatModal()" class="w-full sm:w-auto">
                    Batal
                </x-button.secondary>
                <x-button.primary type="button" onclick="confirmSeatSelection()" id="confirmSeatBtn" disabled class="w-full sm:w-auto">
                    Gunakan Kursi
                </x-button.primary>
            </div>
        </div>
    </div>

    <script>
        let selectedSeats = [];
        const maxAvailableSeats = {{ $trip->available_seats }}; // Maximum seats available in trip
        let maxSeats = maxAvailableSeats; // Will be updated from seats_count input
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

            // Update maxSeats when seats_count input changes
            const seatsCountInput = document.getElementById('seats_count');
            if (seatsCountInput) {
                const maxFromInput = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;

                seatsCountInput.addEventListener('change', function() {
                    const inputValue = parseInt(this.value);
                    const maxAllowed = parseInt(this.getAttribute('data-max-seats')) || maxAvailableSeats;

                    // Ensure input doesn't exceed max available
                    if (inputValue > maxAllowed) {
                        this.value = maxAllowed;
                        maxSeats = maxAllowed;
                    } else {
                        maxSeats = inputValue || maxAllowed;
                    }

                    updateSeatInfo();
                    // If current selection exceeds new limit, remove excess seats
                    if (selectedSeats.length > maxSeats) {
                        selectedSeats = selectedSeats.slice(0, maxSeats);
                        generateSeatMap();
                        updateSeatInputs();
                        updateSeatInfo();
                        alert('Jumlah kursi yang dipilih melebihi batas. Kursi dipilih disesuaikan.');
                    }
                });
                // Initialize maxSeats from current input value or max available
                maxSeats = parseInt(seatsCountInput.value) || maxFromInput;
            }
        });

        async function openSeatModal() {
            // Update maxSeats from current input value before opening modal
            const seatsCountInput = document.getElementById('seats_count');
            if (seatsCountInput) {
                const inputValue = parseInt(seatsCountInput.value);
                const maxAllowed = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;
                maxSeats = inputValue || maxAllowed;
            }

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
                seatMap.innerHTML = '<div class="text-center py-8 text-gray-500">Tidak ada data kursi</div>';
                return;
            }

            // Group seats by row
            const seatsByRow = {};
            seatLayout.forEach(seat => {
                const row = seat.row_index;
                if (!seatsByRow[row]) {
                    seatsByRow[row] = [];
                }
                seatsByRow[row].push(seat);
            });

            // Get sorted row indices
            const sortedRows = Object.keys(seatsByRow).sort((a, b) => parseInt(a) - parseInt(b));
            const maxColIndex = 3; // 0-based, so max is 3 for 4 columns

            // Create Pintu Masuk row (above row A, aligned with column 1 - A1)
            const pintuMasukRow = document.createElement('div');
            pintuMasukRow.className = 'grid items-center';
            pintuMasukRow.style.gridTemplateColumns = '56px 56px 16px 56px 56px 56px';
            pintuMasukRow.style.gap = '4px';
            pintuMasukRow.style.margin = '0';
            pintuMasukRow.style.marginBottom = '4px';
            // Pintu Masuk in column 1 (left side)
            const pintuMasukBtn = document.createElement('div');
            pintuMasukBtn.className = 'w-14 h-8 bg-gradient-to-r from-green-500 to-green-600 border border-green-700 rounded flex items-center justify-center shadow-sm';
            pintuMasukBtn.innerHTML = '<span class="text-[10px] font-bold text-white leading-none">MASUK</span>';
            pintuMasukRow.appendChild(pintuMasukBtn);
            // Empty cell for column 2
            const empty1 = document.createElement('div');
            empty1.className = 'w-14 h-8';
            pintuMasukRow.appendChild(empty1);
            // Gang indicator (column 3) - DIPERKECIL
            const gangIndicator1 = document.createElement('div');
            gangIndicator1.className = 'h-8 bg-yellow-100 border border-yellow-400 rounded flex items-center justify-center';
            gangIndicator1.style.width = '16px';
            gangIndicator1.innerHTML = '<span class="text-[9px] font-bold text-yellow-700">↕</span>';
            pintuMasukRow.appendChild(gangIndicator1);
            // Empty cell for column 4
            const empty2 = document.createElement('div');
            empty2.className = 'w-14 h-8';
            pintuMasukRow.appendChild(empty2);
            // Empty cell for column 5 (driver space)
            const empty3 = document.createElement('div');
            empty3.className = 'w-14 h-8';
            pintuMasukRow.appendChild(empty3);
            seatMap.appendChild(pintuMasukRow);

            // Create Driver row (above row A, aligned with column 4 - A4)
            const driverRow = document.createElement('div');
            driverRow.className = 'grid items-center';
            driverRow.style.gridTemplateColumns = '56px 56px 16px 56px 56px 56px';
            driverRow.style.gap = '4px';
            driverRow.style.margin = '0';
            driverRow.style.marginBottom = '4px';
            // Empty cells for columns 1-2
            for (let i = 0; i < 2; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'w-14 h-8';
                driverRow.appendChild(emptyCell);
            }
            // Gang indicator (column 3) - DIPERKECIL
            const gangIndicator2 = document.createElement('div');
            gangIndicator2.className = 'h-8 bg-yellow-100 border border-yellow-400 rounded flex items-center justify-center';
            gangIndicator2.style.width = '16px';
            gangIndicator2.innerHTML = '<span class="text-[9px] font-bold text-yellow-700">↕</span>';
            driverRow.appendChild(gangIndicator2);
            // Empty cell for column 4
            const empty4 = document.createElement('div');
            empty4.className = 'w-14 h-8';
            driverRow.appendChild(empty4);
            // Driver in column 5 (right side)
            const driverBtn = document.createElement('div');
            driverBtn.className = 'w-14 h-8 bg-gradient-to-r from-gray-400 to-gray-500 border border-gray-600 rounded flex items-center justify-center shadow-sm';
            driverBtn.innerHTML = '<span class="text-[10px] font-bold text-white leading-none">DRIVER</span>';
            driverRow.appendChild(driverBtn);
            seatMap.appendChild(driverRow);

            // Create seat rows (A-H) with 2-2 layout and gang in middle
            sortedRows.forEach(rowIndex => {
                const row = parseInt(rowIndex);
                const rowSeats = seatsByRow[row].sort((a, b) => a.col_index - b.col_index);

                // Create row container: 2 seats left, gang, 2 seats right - GAP MINIMAL
                const rowContainer = document.createElement('div');
                rowContainer.className = 'grid items-stretch';
                rowContainer.style.gridTemplateColumns = '56px 56px 16px 56px 56px 56px';
                rowContainer.style.gap = '4px';
                rowContainer.style.margin = '0';
                rowContainer.style.marginBottom = '4px';
                rowContainer.style.padding = '0';
                rowContainer.style.lineHeight = '0';
                rowContainer.style.fontSize = '0';

                // Left side seats (columns 0-1, which are A1-A2, B1-B2, etc.)
                for (let col = 0; col <= 1; col++) {
                    const seat = rowSeats.find(s => s.col_index === col);

                    if (seat) {
                        const seatBtn = document.createElement('button');
                        seatBtn.type = 'button';
                        seatBtn.className = 'w-14 h-14 rounded border-2 border-gray-300 transition-all duration-200 font-semibold text-xs flex items-center justify-center';
                        seatBtn.style.margin = '0';
                        seatBtn.style.padding = '0';
                        seatBtn.style.boxSizing = 'border-box';
                        seatBtn.style.lineHeight = '1';
                        seatBtn.style.fontSize = '12px';
                        seatBtn.textContent = seat.seat_number;
                        seatBtn.dataset.seatNumber = seat.seat_number;
                        seatBtn.onclick = () => toggleSeat(seat.seat_number);

                        if (seat.status === 'booked') {
                            seatBtn.classList.add('bg-gray-400', 'border-gray-500', 'cursor-not-allowed', 'text-white', 'opacity-70');
                            seatBtn.disabled = true;
                        } else {
                            seatBtn.classList.add('bg-white', 'hover:border-sky-500', 'hover:bg-sky-50', 'text-gray-800');
                        }

                        if (selectedSeats.includes(seat.seat_number)) {
                            seatBtn.classList.remove('bg-white', 'border-gray-300', 'hover:border-sky-500', 'hover:bg-sky-50', 'text-gray-800');
                            seatBtn.classList.add('bg-sky-600', 'border-sky-700', 'text-white', 'font-bold');
                        }

                        rowContainer.appendChild(seatBtn);
                    } else {
                        const emptyCell = document.createElement('div');
                        emptyCell.className = 'w-14 h-14';
                        emptyCell.style.margin = '0';
                        emptyCell.style.padding = '0';
                        emptyCell.style.boxSizing = 'border-box';
                        rowContainer.appendChild(emptyCell);
                    }
                }

                // Gang in middle (column 3) - DIPERKECIL
                const gangCell = document.createElement('div');
                gangCell.className = 'h-14 bg-yellow-50 border-l border-r border-dashed border-yellow-300 flex items-center justify-center';
                gangCell.style.width = '16px';
                gangCell.style.margin = '0';
                gangCell.style.padding = '0';
                gangCell.style.boxSizing = 'border-box';
                gangCell.innerHTML = '<div class="w-0.5 h-full bg-yellow-400"></div>';
                rowContainer.appendChild(gangCell);

                // Right side seats (columns 2-3, which are A3-A4, B3-B4, etc.)
                for (let col = 2; col <= 3; col++) {
                    const seat = rowSeats.find(s => s.col_index === col);

                    if (seat) {
                        const seatBtn = document.createElement('button');
                        seatBtn.type = 'button';
                        seatBtn.className = 'w-14 h-14 rounded border-2 border-gray-300 transition-all duration-200 font-semibold text-xs flex items-center justify-center';
                        seatBtn.style.margin = '0';
                        seatBtn.style.padding = '0';
                        seatBtn.style.boxSizing = 'border-box';
                        seatBtn.style.lineHeight = '1';
                        seatBtn.style.fontSize = '12px';
                        seatBtn.textContent = seat.seat_number;
                        seatBtn.dataset.seatNumber = seat.seat_number;
                        seatBtn.onclick = () => toggleSeat(seat.seat_number);

                        if (seat.status === 'booked') {
                            seatBtn.classList.add('bg-gray-400', 'border-gray-500', 'cursor-not-allowed', 'text-white', 'opacity-70');
                            seatBtn.disabled = true;
                        } else {
                            seatBtn.classList.add('bg-white', 'hover:border-sky-500', 'hover:bg-sky-50', 'text-gray-800');
                        }

                        if (selectedSeats.includes(seat.seat_number)) {
                            seatBtn.classList.remove('bg-white', 'border-gray-300', 'hover:border-sky-500', 'hover:bg-sky-50', 'text-gray-800');
                            seatBtn.classList.add('bg-sky-600', 'border-sky-700', 'text-white', 'font-bold');
                        }

                        rowContainer.appendChild(seatBtn);
                    } else {
                        const emptyCell = document.createElement('div');
                        emptyCell.className = 'w-14 h-14';
                        emptyCell.style.margin = '0';
                        emptyCell.style.padding = '0';
                        emptyCell.style.boxSizing = 'border-box';
                        rowContainer.appendChild(emptyCell);
                    }
                }

                // Empty space for driver alignment (column 5)
                const emptySpace = document.createElement('div');
                emptySpace.className = 'w-14 h-14';
                emptySpace.style.margin = '0';
                emptySpace.style.padding = '0';
                emptySpace.style.boxSizing = 'border-box';
                rowContainer.appendChild(emptySpace);

                seatMap.appendChild(rowContainer);
            });

            // Create Pintu Keluar row (below all seats, aligned with column 1 - H1)
            const pintuKeluarRow = document.createElement('div');
            pintuKeluarRow.className = 'grid items-center';
            pintuKeluarRow.style.gridTemplateColumns = '56px 56px 16px 56px 56px 56px';
            pintuKeluarRow.style.gap = '4px';
            pintuKeluarRow.style.margin = '0';
            pintuKeluarRow.style.marginTop = '4px';
            // Pintu Keluar in column 1 (left side)
            const pintuKeluarBtn = document.createElement('div');
            pintuKeluarBtn.className = 'w-14 h-8 bg-gradient-to-r from-red-500 to-red-600 border border-red-700 rounded flex items-center justify-center shadow-sm';
            pintuKeluarBtn.innerHTML = '<span class="text-[10px] font-bold text-white leading-none">KELUAR</span>';
            pintuKeluarRow.appendChild(pintuKeluarBtn);
            // Empty cell for column 2
            const empty5 = document.createElement('div');
            empty5.className = 'w-14 h-8';
            pintuKeluarRow.appendChild(empty5);
            // Gang indicator (column 3) - DIPERKECIL
            const gangIndicator3 = document.createElement('div');
            gangIndicator3.className = 'h-8 bg-yellow-100 border border-yellow-400 rounded flex items-center justify-center';
            gangIndicator3.style.width = '16px';
            gangIndicator3.innerHTML = '<span class="text-[9px] font-bold text-yellow-700">↕</span>';
            pintuKeluarRow.appendChild(gangIndicator3);
            // Empty cells for columns 4-5
            for (let i = 0; i < 2; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'w-14 h-8';
                pintuKeluarRow.appendChild(emptyCell);
            }
            seatMap.appendChild(pintuKeluarRow);

            // Scale seat map to fit container after rendering
            setTimeout(() => {
                const container = seatMap.parentElement;
                const containerWidth = container.clientWidth - 24; // minus padding
                const containerHeight = container.clientHeight - 24; // minus padding

                const seatMapWidth = seatMap.scrollWidth;
                const seatMapHeight = seatMap.scrollHeight;

                if (seatMapWidth > 0 && seatMapHeight > 0) {
                    const scaleX = containerWidth / seatMapWidth;
                    const scaleY = containerHeight / seatMapHeight;
                    const scale = Math.min(scaleX, scaleY, 1); // Don't scale up, only down

                    seatMap.style.transform = `scale(${scale})`;
                    seatMap.style.transformOrigin = 'top center';
                }
            }, 50);
        }

        async function recommendSeats() {
            const seatsCountInput = document.getElementById('seats_count');
            const seatsCount = parseInt(seatsCountInput.value || '1', 10);
            const maxAllowed = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;

            // Update maxSeats from input, but don't exceed max available
            maxSeats = Math.min(seatsCount, maxAllowed);

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
                    seatsCountInput.value = selectedSeats.length;
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
                // Update maxSeats from input before checking
                const seatsCountInput = document.getElementById('seats_count');
                if (seatsCountInput) {
                    const inputValue = parseInt(seatsCountInput.value);
                    const maxAllowed = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;
                    maxSeats = inputValue || maxAllowed;
                }

                if (selectedSeats.length < maxSeats) {
                    selectedSeats.push(seatNumber);
                } else {
                    alert('Maksimal ' + maxSeats + ' kursi yang dapat dipilih. Silakan ubah jumlah kursi di form jika ingin memilih lebih banyak.');
                    return;
                }
            }
            generateSeatMap();
            updateSeatInfo();
        }

        function updateSeatInfo() {
            // Update maxSeats from input
            const seatsCountInput = document.getElementById('seats_count');
            if (seatsCountInput) {
                const inputValue = parseInt(seatsCountInput.value);
                const maxAllowed = parseInt(seatsCountInput.getAttribute('data-max-seats')) || maxAvailableSeats;
                maxSeats = inputValue || maxAllowed;
            }

            document.getElementById('modalSelectedCount').textContent = selectedSeats.length;
            document.getElementById('modalMaxSeats').textContent = maxSeats;
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
