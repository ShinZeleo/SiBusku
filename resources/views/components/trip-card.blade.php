@props(['trip'])

@php
    $departureDate = \Carbon\Carbon::parse($trip->departure_date)->format('d M Y');
@endphp

<div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
    <div class="flex-1 space-y-3">
        <div>
            <p class="text-xs font-semibold tracking-widest text-slate-500 uppercase">Rute</p>
            <h3 class="text-xl font-semibold text-slate-900">{{ $trip->route->origin_city }} &mdash; {{ $trip->route->destination_city }}</h3>
        </div>
        <div class="grid gap-3 text-sm text-slate-600 md:grid-cols-2">
            <div>
                <p class="font-medium text-slate-800">Bus</p>
                <p>{{ $trip->bus->name }} · {{ $trip->bus->bus_class }}</p>
            </div>
            <div>
                <p class="font-medium text-slate-800">Tanggal</p>
                <p>{{ $departureDate }} · {{ $trip->departure_time }}</p>
            </div>
            <div>
                <p class="font-medium text-slate-800">Durasi</p>
                <p>{{ $trip->route->duration_estimate }} jam</p>
            </div>
            <div>
                <p class="font-medium text-slate-800">Kursi Tersedia</p>
                <p>{{ $trip->available_seats }} kursi</p>
            </div>
        </div>
    </div>
    <div class="w-full md:w-auto md:text-right space-y-4">
        <div>
            <p class="text-xs uppercase tracking-widest text-slate-500">Mulai dari</p>
            <p class="text-2xl font-bold text-slate-900">{{ $trip->price_formatted }}</p>
        </div>
        <div>
            @auth
                <button onclick="openBookingModal({{ $trip->id }})" class="w-full md:w-auto px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">Pesan</button>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full md:w-auto px-6 py-2 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition">Login untuk Pesan</a>
            @endauth
        </div>
    </div>
</div>
