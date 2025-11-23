<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="space-y-12">
        <!-- Hero Section with Search Form -->
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-sky-600 via-sky-700 to-blue-700 px-8 py-12 text-white shadow-xl">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-4xl font-extrabold leading-tight mb-2">SIBUSKU</h1>
                        <p class="text-sky-100/80">Cari dan pesan tiket bus dengan mudah</p>
                    </div>
                    <div class="flex gap-4">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">Dashboard</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition">Login</a>
                            <a href="<?php echo e(route('register')); ?>" class="px-4 py-2 bg-white text-black hover:text-sky-700 hover:border-sky-700 rounded-lg transition">Register</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-lg font-semibold mb-2">Masukkan kota asal, tujuan, dan tanggal</p>
                </div>

                <!-- Search Form -->
                <form action="<?php echo e(route('search.trips')); ?>" method="POST" class="bg-white rounded-2xl p-6 shadow-xl">
                    <?php echo csrf_field(); ?>
                    <div class="grid gap-4 md:grid-cols-4">
                        <div>
                            <label for="origin_city" class="block text-sm font-semibold text-gray-700 mb-2">Kota Asal</label>
                            <select
                                name="origin_city"
                                id="origin_city"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                required
                            >
                                <option value="">Pilih Kota Asal</option>
                                <?php $__currentLoopData = $originCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($city); ?>" <?php if(old('origin_city') === $city): echo 'selected'; endif; ?>><?php echo e($city); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label for="destination_city" class="block text-sm font-semibold text-gray-700 mb-2">Kota Tujuan</label>
                            <select
                                name="destination_city"
                                id="destination_city"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                required
                            >
                                <option value="">Pilih Kota Tujuan</option>
                                <?php $__currentLoopData = $destinationCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($city); ?>" <?php if(old('destination_city') === $city): echo 'selected'; endif; ?>><?php echo e($city); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label for="departure_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                            <input
                                type="date"
                                name="departure_date"
                                id="departure_date"
                                min="<?php echo e(now()->format('Y-m-d')); ?>"
                                value="<?php echo e(old('departure_date', now()->format('Y-m-d'))); ?>"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40"
                                required
                            >
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full rounded-xl bg-sky-600 hover:bg-sky-700 px-6 py-3 text-white font-semibold shadow-lg transition">
                                CARI TRIP
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Information -->
                <div class="mt-6 text-sm text-sky-100/90">
                    <p class="mb-2">Informasi:</p>
                    <div class="flex flex-col gap-1">
                        <p>▢ Notifikasi akan dikirim lewat WhatsApp</p>
                        <p>▢ Pastikan nomor kamu benar</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search Results Section (if coming from search) -->
        <?php if(request()->has('search') && isset($trips)): ?>
            <section class="space-y-6">
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Hasil Pencarian:</h2>

                    <?php if(isset($trips) && $trips->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div class="flex-1">
                                            <p class="text-lg font-bold text-gray-900 mb-1">
                                                <?php echo e(strtoupper($trip->route->origin_city)); ?> → <?php echo e(strtoupper($trip->route->destination_city)); ?>

                                            </p>
                                            <p class="text-sm text-gray-600 mb-2">
                                                <?php echo e(\Carbon\Carbon::parse($trip->departure_date)->format('d M Y')); ?>, <?php echo e(\Carbon\Carbon::parse($trip->departure_time)->format('H.i')); ?>

                                            </p>
                                            <p class="text-sm text-gray-700">
                                                Bus <?php echo e($trip->bus->name); ?> | <?php echo e($trip->bus->bus_class); ?>

                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Sisa Kursi: <?php echo e($trip->available_seats); ?>

                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <p class="text-xl font-bold text-gray-900">Harga: <?php echo e($trip->price_formatted); ?></p>
                                            <?php if(auth()->guard()->check()): ?>
                                                <button
                                                    onclick="openBookingModal(<?php echo e($trip->id); ?>)"
                                                    class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
                                                >
                                                    PESAN TIKET
                                                </button>
                                            <?php else: ?>
                                                <a
                                                    href="<?php echo e(route('login')); ?>"
                                                    class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-xl transition"
                                                >
                                                    PESAN TIKET
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
                            <p class="text-gray-600">Tidak ditemukan trip untuk rute dan tanggal yang dicari.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Upcoming Trips -->
        <?php if($trips->count() > 0): ?>
            <section class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">Rekomendasi Perjalanan</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginalf40af3336376e041603598099dbd5fdc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf40af3336376e041603598099dbd5fdc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.trip-card','data' => ['trip' => $trip]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('trip-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['trip' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($trip)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf40af3336376e041603598099dbd5fdc)): ?>
<?php $attributes = $__attributesOriginalf40af3336376e041603598099dbd5fdc; ?>
<?php unset($__attributesOriginalf40af3336376e041603598099dbd5fdc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf40af3336376e041603598099dbd5fdc)): ?>
<?php $component = $__componentOriginalf40af3336376e041603598099dbd5fdc; ?>
<?php unset($__componentOriginalf40af3336376e041603598099dbd5fdc); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/home.blade.php ENDPATH**/ ?>