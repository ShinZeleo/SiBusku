@props([
    'seatLimit' => 4,
    'formId' => 'bookingForm',
    'modalId' => 'bookingModal',
    'seats' => null,
])

@php
    $defaultSeats = [];
    foreach (range('A', 'F') as $row) {
        foreach (range(1, 4) as $number) {
            $defaultSeats[] = $row . $number;
        }
    }
    $seatList = $seats ?? $defaultSeats;
@endphp

<div
    class="bg-white border rounded-xl p-5 shadow-sm"
    x-data="{
        seats: @js($seatList),
        seatLimit: {{ (int) $seatLimit }},
        selectedSeats: [],
        formId: '{{ $formId }}',
        modalId: '{{ $modalId }}',
        get leftSeats() {
            const midpoint = Math.ceil(this.seats.length / 2);
            return this.seats.slice(0, midpoint);
        },
        get rightSeats() {
            const midpoint = Math.ceil(this.seats.length / 2);
            return this.seats.slice(midpoint);
        },
        isSelected(seat) {
            return this.selectedSeats.includes(seat);
        },
        isDisabled(seat) {
            return !this.isSelected(seat) && this.selectedSeats.length >= this.seatLimit;
        },
        toggleSeat(seat) {
            if (this.isSelected(seat)) {
                this.selectedSeats = this.selectedSeats.filter(item => item !== seat);
                return;
            }

            if (this.selectedSeats.length >= this.seatLimit) {
                return;
            }

            this.selectedSeats.push(seat);
        },
        clearSelection() {
            this.selectedSeats = [];
        },
        confirmSelection() {
            if (this.selectedSeats.length === 0) {
                return;
            }

            const seatsCountInput = this.$refs.seatsCountInput;
            const selectedSeatsInput = this.$refs.selectedSeatsInput;
            const form = document.getElementById(this.formId);

            if (seatsCountInput) {
                seatsCountInput.value = this.selectedSeats.length;
            }

            if (selectedSeatsInput) {
                selectedSeatsInput.value = this.selectedSeats.join(',');
            }

            if (!form) {
                return;
            }

            if (!form.reportValidity()) {
                return;
            }

            form.requestSubmit();

            if (typeof closeBookingModal === 'function') {
                closeBookingModal();
            } else if (this.modalId) {
                const modal = document.getElementById(this.modalId);
                if (modal) {
                    modal.classList.add('hidden');
                }
            }
        }
    }"
>
    <div class="flex items-stretch gap-8">
        <div class="grid grid-cols-2 gap-3 flex-1">
            <template x-for="seat in leftSeats" :key="seat">
                <button
                    type="button"
                    class="py-2 rounded-lg border text-sm font-semibold transition focus:outline-none"
                    :class="{
                        'bg-indigo-600 border-indigo-600 text-white shadow': isSelected(seat),
                        'bg-slate-50 border-slate-200 text-gray-700 hover:border-indigo-300 hover:text-indigo-600': !isSelected(seat),
                        'opacity-40 cursor-not-allowed': isDisabled(seat)
                    }"
                    :disabled="isDisabled(seat)"
                    x-text="seat"
                    @click="toggleSeat(seat)"
                ></button>
            </template>
        </div>

        <div class="w-6 flex items-stretch">
            <div class="w-px bg-gray-200 mx-auto"></div>
        </div>

        <div class="grid grid-cols-2 gap-3 flex-1">
            <template x-for="seat in rightSeats" :key="seat">
                <button
                    type="button"
                    class="py-2 rounded-lg border text-sm font-semibold transition focus:outline-none"
                    :class="{
                        'bg-indigo-600 border-indigo-600 text-white shadow': isSelected(seat),
                        'bg-slate-50 border-slate-200 text-gray-700 hover:border-indigo-300 hover:text-indigo-600': !isSelected(seat),
                        'opacity-40 cursor-not-allowed': isDisabled(seat)
                    }"
                    :disabled="isDisabled(seat)"
                    x-text="seat"
                    @click="toggleSeat(seat)"
                ></button>
            </template>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm text-gray-600">
                <span class="font-semibold" x-text="`Kursi dipilih: ${selectedSeats.length}`"></span>
                dari
                <span class="font-semibold" x-text="seatLimit"></span>
                kursi.
            </p>
            <p class="text-xs text-gray-500" x-show="selectedSeats.length >= seatLimit">
                Batas maksimal kursi tercapai.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button
                type="button"
                class="px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50"
                @click="clearSelection"
            >
                Reset
            </button>
            <button
                type="button"
                class="px-4 py-2 text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed"
                :disabled="selectedSeats.length === 0"
                @click="confirmSelection"
            >
                Konfirmasi &amp; Pesan
            </button>
        </div>
    </div>

    <input type="hidden" name="seats_count" x-ref="seatsCountInput" value="0">
    <input type="hidden" name="selected_seats" x-ref="selectedSeatsInput" value="">
</div>
