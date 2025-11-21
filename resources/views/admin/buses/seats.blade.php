<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
                <div class="p-6">
                    <div class="mb-6 border-b border-gray-100 pb-4">
                        <h1 class="text-2xl font-bold text-gray-900">Kelola Layout Kursi Bus</h1>
                        <p class="text-sm text-gray-500 mt-1">Bus: {{ $bus->name }} ({{ $bus->bus_class }})</p>
                    </div>

                    <form action="{{ route('admin.buses.seats.update', $bus->id) }}" method="POST" id="seatLayoutForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Layout Kursi (Format: Nomor Kursi, Baris, Kolom)
                            </label>
                            <div class="space-y-2" id="seatsContainer">
                                @if($seats->count() > 0)
                                    @foreach($seats as $index => $seat)
                                        <div class="flex gap-3 items-center seat-row">
                                            <input
                                                type="text"
                                                name="seats[{{ $index }}][seat_number]"
                                                value="{{ $seat->seat_number }}"
                                                placeholder="A1"
                                                class="w-24 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                                required
                                            >
                                            <input
                                                type="number"
                                                name="seats[{{ $index }}][row_index]"
                                                value="{{ $seat->row_index }}"
                                                placeholder="Baris (0,1,2...)"
                                                class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                                required
                                                min="0"
                                            >
                                            <input
                                                type="number"
                                                name="seats[{{ $index }}][col_index]"
                                                value="{{ $seat->col_index }}"
                                                placeholder="Kolom (0,1,2...)"
                                                class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                                required
                                                min="0"
                                            >
                                            <select
                                                name="seats[{{ $index }}][section]"
                                                class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                            >
                                                <option value="">-</option>
                                                <option value="front" {{ $seat->section === 'front' ? 'selected' : '' }}>Front</option>
                                                <option value="middle" {{ $seat->section === 'middle' ? 'selected' : '' }}>Middle</option>
                                                <option value="back" {{ $seat->section === 'back' ? 'selected' : '' }}>Back</option>
                                            </select>
                                            <button
                                                type="button"
                                                onclick="removeSeatRow(this)"
                                                class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default: Generate berdasarkan kapasitas -->
                                    @for($i = 0; $i < $bus->capacity; $i++)
                                        @php
                                            $row = intval($i / 4);
                                            $col = $i % 4;
                                            $seatNumber = chr(65 + $row) . ($col + 1);
                                        @endphp
                                        <div class="flex gap-3 items-center seat-row">
                                            <input
                                                type="text"
                                                name="seats[{{ $i }}][seat_number]"
                                                value="{{ $seatNumber }}"
                                                placeholder="A1"
                                                class="w-24 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                                required
                                            >
                                            <input
                                                type="number"
                                                name="seats[{{ $i }}][row_index]"
                                                value="{{ $row }}"
                                                placeholder="Baris"
                                                class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                                required
                                                min="0"
                                            >
                                            <input
                                                type="number"
                                                name="seats[{{ $i }}][col_index]"
                                                value="{{ $col }}"
                                                placeholder="Kolom"
                                                class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                                required
                                                min="0"
                                            >
                                            <select
                                                name="seats[{{ $i }}][section]"
                                                class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                                            >
                                                <option value="">-</option>
                                                <option value="front">Front</option>
                                                <option value="middle">Middle</option>
                                                <option value="back">Back</option>
                                            </select>
                                            <button
                                                type="button"
                                                onclick="removeSeatRow(this)"
                                                class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    @endfor
                                @endif
                            </div>

                            <button
                                type="button"
                                onclick="addSeatRow()"
                                class="mt-4 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
                            >
                                + Tambah Kursi
                            </button>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.buses.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition">
                                Simpan Layout
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let seatIndex = {{ $seats->count() > 0 ? $seats->count() : $bus->capacity }};

        function addSeatRow() {
            const container = document.getElementById('seatsContainer');
            const row = document.createElement('div');
            row.className = 'flex gap-3 items-center seat-row';
            row.innerHTML = `
                <input
                    type="text"
                    name="seats[${seatIndex}][seat_number]"
                    placeholder="A1"
                    class="w-24 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                    required
                >
                <input
                    type="number"
                    name="seats[${seatIndex}][row_index]"
                    placeholder="Baris (0,1,2...)"
                    class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                    required
                    min="0"
                >
                <input
                    type="number"
                    name="seats[${seatIndex}][col_index]"
                    placeholder="Kolom (0,1,2...)"
                    class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                    required
                    min="0"
                >
                <select
                    name="seats[${seatIndex}][section]"
                    class="w-32 rounded-xl border border-gray-300 px-3 py-2 text-sm"
                >
                    <option value="">-</option>
                    <option value="front">Front</option>
                    <option value="middle">Middle</option>
                    <option value="back">Back</option>
                </select>
                <button
                    type="button"
                    onclick="removeSeatRow(this)"
                    class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm"
                >
                    Hapus
                </button>
            `;
            container.appendChild(row);
            seatIndex++;
        }

        function removeSeatRow(button) {
            if (confirm('Hapus kursi ini?')) {
                button.closest('.seat-row').remove();
            }
        }
    </script>
</x-app-layout>

