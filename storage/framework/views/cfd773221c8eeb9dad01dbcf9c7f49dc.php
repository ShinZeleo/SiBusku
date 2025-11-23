<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['trip', 'showLink' => true]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['trip', 'showLink' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-4">
    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Rute Perjalanan</p>
            <p class="text-2xl font-bold text-gray-900">
                <?php echo e($trip->route->origin_city); ?>

                <span class="text-indigo-500">&rarr;</span>
                <?php echo e($trip->route->destination_city); ?>

            </p>
            <p class="text-sm text-gray-500">Durasi estimasi <?php echo e($trip->route->duration_estimate); ?> jam</p>
        </div>
        <div class="text-left md:text-right">
            <p class="text-sm text-gray-500">Mulai dari</p>
            <p class="text-3xl font-bold text-indigo-600"><?php echo e($trip->price_formatted); ?></p>
            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                <?php echo e($trip->available_seats); ?> kursi tersedia
            </span>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3 text-sm text-gray-700">
        <div class="rounded-xl bg-gray-50 p-3">
            <p class="text-xs uppercase tracking-wide text-gray-500">Tanggal</p>
            <p class="font-semibold text-gray-900"><?php echo e($trip->departure_date_formatted); ?></p>
        </div>
        <div class="rounded-xl bg-gray-50 p-3">
            <p class="text-xs uppercase tracking-wide text-gray-500">Waktu</p>
            <p class="font-semibold text-gray-900"><?php echo e($trip->departure_time); ?></p>
        </div>
        <div class="rounded-xl bg-gray-50 p-3">
            <p class="text-xs uppercase tracking-wide text-gray-500">Bus</p>
            <p class="font-semibold text-gray-900"><?php echo e($trip->bus->name); ?> &middot; <?php echo e($trip->bus->bus_class); ?></p>
        </div>
    </div>

    <?php if($showLink && $trip->status === 'scheduled'): ?>
        <div class="pt-4 border-t border-gray-100">
            <a
                href="<?php echo e(route('trips.show', $trip->id)); ?>"
                class="inline-flex items-center justify-center w-full px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
            >
                Lihat Detail & Booking
            </a>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/components/trip-card.blade.php ENDPATH**/ ?>