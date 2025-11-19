@props(['trip'])

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-4">
    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Rute Perjalanan</p>
            <p class="text-2xl font-bold text-gray-900">
                {{ $trip->route->origin_city }}
                <span class="text-indigo-500">&rarr;</span>
                {{ $trip->route->destination_city }}
            </p>
            <p class="text-sm text-gray-500">Durasi estimasi {{ $trip->route->duration_estimate }} jam</p>
        </div>
        <div class="text-left md:text-right">
            <p class="text-sm text-gray-500">Mulai dari</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $trip->price_formatted }}</p>
            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                {{ $trip->available_seats }} kursi tersedia
            </span>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3 text-sm text-gray-700">
        <div class="rounded-xl bg-gray-50 p-3">
            <p class="text-xs uppercase tracking-wide text-gray-500">Tanggal</p>
            <p class="font-semibold text-gray-900">{{ $trip->departure_date_formatted }}</p>
        </div>
        <div class="rounded-xl bg-gray-50 p-3">
            <p class="text-xs uppercase tracking-wide text-gray-500">Waktu</p>
            <p class="font-semibold text-gray-900">{{ $trip->departure_time }}</p>
        </div>
        <div class="rounded-xl bg-gray-50 p-3">
            <p class="text-xs uppercase tracking-wide text-gray-500">Bus</p>
            <p class="font-semibold text-gray-900">{{ $trip->bus->name }} &middot; {{ $trip->bus->bus_class }}</p>
        </div>
    </div>
</div>
