<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hasil Pencarian - {{ $originCity }} ke {{ $destinationCity }} pada {{ \Carbon\Carbon::parse($departureDate)->format('d M Y') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Hasil Pencarian</h1>
                    
                    @if($trips->count() > 0)
                        <div class="space-y-4">
                            @foreach($trips as $trip)
                                <x-trip-card :trip="$trip" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <p class="text-lg text-gray-500">Tidak ditemukan trip untuk rute dan tanggal yang dicari.</p>
                            <a href="{{ route('search.form') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Cari Lagi</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Booking Modal -->
    <div id="bookingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h3 class="text-lg font-semibold">Form Booking</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="bookingForm" method="POST" action="{{ route('bookings.store') }}">
                    @csrf
                    <input type="hidden" id="trip_id" name="trip_id" value="">
                    
                    <div class="mb-4">
                        <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="customer_name" id="customer_name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Kursi</label>
                        <x-seat-selector seat-limit="4" form-id="bookingForm" modal-id="bookingModal" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeBookingModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function openBookingModal(tripId) {
            document.getElementById('trip_id').value = tripId;
            document.getElementById('bookingModal').classList.remove('hidden');
        }
        
        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('bookingModal');
            if (event.target == modal) {
                closeBookingModal();
            }
        }
    </script>
</x-app-layout>