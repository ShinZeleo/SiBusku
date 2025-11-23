<x-app-layout>
    <div class="space-y-8 pt-24 pb-16">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Hasil Pencarian</h1>
                <p class="text-gray-600 mt-1">{{ $originCity }} → {{ $destinationCity }} pada {{ \Carbon\Carbon::parse($departureDate)->format('d M Y') }}</p>
            </div>
            <a href="{{ route('home') }}" class="text-sky-600 hover:text-sky-700 font-semibold transition hover:underline">← Kembali ke Beranda</a>
        </div>

        <!-- Search Results -->
        @if($trips->count() > 0)
            <div class="space-y-4">
                @foreach($trips as $trip)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <p class="text-lg font-bold text-gray-900 mb-1">
                                    {{ strtoupper($trip->route->origin_city) }} → {{ strtoupper($trip->route->destination_city) }}
                                </p>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ \Carbon\Carbon::parse($trip->departure_date)->format('d M Y') }}, {{ \Carbon\Carbon::parse($trip->departure_time)->format('H.i') }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    Bus {{ $trip->bus->name }} | {{ $trip->bus->bus_class }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Sisa Kursi: {{ $trip->available_seats }}
                                </p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <p class="text-xl font-bold text-gray-900">Harga: {{ $trip->price_formatted }}</p>
                                @auth
                                    <a
                                        href="{{ route('bookings.create', ['trip_id' => $trip->id]) }}"
                                        class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
                                    >
                                        PESAN TIKET
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
                                    >
                                        PESAN TIKET
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="min-h-[70vh] flex items-center justify-center py-16">
                <div class="max-w-2xl mx-auto px-4 animate-fade-in-up">
                    <!-- Illustration -->
                    <div class="flex justify-center mb-8">
                        <div class="relative w-48 h-48">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full opacity-50"></div>
                            <div class="absolute inset-4 bg-gradient-to-br from-blue-200 to-indigo-200 rounded-full opacity-30"></div>
                            <div class="relative w-full h-full flex items-center justify-center">
                                <svg class="w-24 h-24 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State Card -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-10 shadow-neumorphism-lg border border-white/50">
                        <!-- Previous Search Badge -->
                        <div class="flex justify-center mb-6">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-sm text-gray-700">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-semibold">{{ $originCity }}</span>
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                                <span class="font-semibold">{{ $destinationCity }}</span>
                                <span class="text-gray-400 mx-2">•</span>
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($departureDate)->format('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-3">Tidak ditemukan trip untuk rute dan tanggal yang dicari.</h2>
                            <p class="text-lg text-gray-600">Coba ubah tanggal keberangkatan atau pilih rute lain.</p>
                        </div>

                        <!-- CTA Button -->
                        <div class="flex justify-center">
                            <a href="{{ route('home') }}" class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-full transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 hover:ring-4 hover:ring-blue-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Cari Trip Lainnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Booking Modal with Seat Selection -->
    @auth
        <div id="bookingModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-2xl bg-white">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">PILIH KURSI</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
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
                                    <span id="selectedSeatsCount">0</span> / 4
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Nomor Kursi:</span>
                                    <span id="selectedSeatsNumbers">-</span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Total Harga:</span>
                                    <span id="totalPrice">Rp 0</span>
                                </p>
                            </div>
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
                            <div id="seatMap" class="grid grid-cols-4 gap-3">
                                <!-- Seats will be generated by JavaScript -->
                            </div>

                            <p class="text-center text-sm text-gray-600 mt-6">
                                Silakan pilih posisi duduk di kiri
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        onclick="closeBookingModal()"
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
            let selectedTripId = null;
            let selectedSeats = [];
            let tripPrice = 0;
            const maxSeats = 4;

            function openBookingModal(tripId, price) {
                selectedTripId = tripId;
                tripPrice = price;
                selectedSeats = [];

                // Reset modal
                document.getElementById('bookingModal').classList.remove('hidden');
                generateSeatMap();
                updateSeatInfo();
            }

            function closeBookingModal() {
                document.getElementById('bookingModal').classList.add('hidden');
                selectedSeats = [];
                selectedTripId = null;
            }

            function generateSeatMap() {
                const seatMap = document.getElementById('seatMap');
                seatMap.innerHTML = '';

                // Generate seats (4 rows x 4 columns = 16 seats)
                // Format: A1, A2, A3, A4, B1, B2, etc.
                const rows = ['A', 'B', 'C', 'D'];
                rows.forEach(row => {
                    for (let col = 1; col <= 4; col++) {
                        const seatNumber = row + col;
                        const seatBtn = document.createElement('button');
                        seatBtn.type = 'button';
                        seatBtn.className = 'w-12 h-12 rounded border-2 transition';
                        seatBtn.textContent = seatNumber;
                        seatBtn.onclick = () => toggleSeat(seatNumber);

                        // Randomly mark some seats as occupied (for demo)
                        const isOccupied = Math.random() > 0.7;
                        if (isOccupied) {
                            seatBtn.classList.add('bg-gray-400', 'border-gray-400', 'cursor-not-allowed');
                            seatBtn.disabled = true;
                        } else {
                            seatBtn.classList.add('bg-white', 'border-gray-300', 'hover:border-sky-500');
                        }

                        seatMap.appendChild(seatBtn);
                    }
                });
            }

            function toggleSeat(seatNumber) {
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
                updateSeatMap();
                updateSeatInfo();
            }

            function updateSeatMap() {
                const seatButtons = document.querySelectorAll('#seatMap button');
                seatButtons.forEach(btn => {
                    const seatNumber = btn.textContent;
                    if (selectedSeats.includes(seatNumber)) {
                        btn.classList.remove('bg-white', 'border-gray-300');
                        btn.classList.add('bg-sky-600', 'border-sky-600', 'text-white');
                    } else if (!btn.disabled) {
                        btn.classList.remove('bg-sky-600', 'border-sky-600', 'text-white');
                        btn.classList.add('bg-white', 'border-gray-300');
                    }
                });
            }

            function updateSeatInfo() {
                document.getElementById('selectedSeatsCount').textContent = selectedSeats.length;
                document.getElementById('selectedSeatsNumbers').textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : '-';

                // Calculate total price
                const total = selectedSeats.length * tripPrice;
                document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');

                // Enable/disable confirm button
                const confirmBtn = document.getElementById('confirmSeatBtn');
                confirmBtn.disabled = selectedSeats.length === 0;
            }

            function confirmSeatSelection() {
                if (selectedSeats.length === 0) {
                    alert('Pilih minimal 1 kursi');
                    return;
                }

                // Redirect to booking form with selected seats
                window.location.href = `{{ route('bookings.create') }}?trip_id=${selectedTripId}&seats=${selectedSeats.join(',')}`;
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                const modal = document.getElementById('bookingModal');
                if (event.target == modal) {
                    closeBookingModal();
                }
            }
        </script>

    @endauth

    <!-- Custom Styles -->
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
            opacity: 0;
        }

        .shadow-neumorphism-lg {
            box-shadow: 12px 12px 24px rgba(0, 0, 0, 0.08), -12px -12px 24px rgba(255, 255, 255, 0.8);
        }
    </style>
</x-app-layout>