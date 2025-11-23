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
    <div class="space-y-16 pb-16">
        <!-- Hero Section with Search Card -->
        <section class="relative pt-24 pb-20 overflow-hidden">
            <!-- Background Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-indigo-50"></div>
            
            <!-- Floating Illustration -->
            <div class="absolute top-20 right-10 w-64 h-64 opacity-10 hidden lg:block">
                <svg class="w-full h-full text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <div class="absolute bottom-20 left-10 w-48 h-48 opacity-5 hidden lg:block">
                <svg class="w-full h-full text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Hero Title -->
                <div class="text-center mb-12 animate-fade-in-up" style="animation-delay: 0.1s">
                    <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-4">
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">SIBUSKU</span>
                    </h1>
                    <p class="text-xl text-gray-600">Cari dan pesan tiket bus dengan mudah</p>
                </div>

                <!-- Search Card with Glassmorphism -->
                <div class="animate-fade-in-up" style="animation-delay: 0.2s">
                    <form action="<?php echo e(route('search.trips')); ?>" method="POST" class="bg-white/80 backdrop-blur-md rounded-3xl p-8 shadow-neumorphism-lg border border-white/50">
                        <?php echo csrf_field(); ?>
                        <div class="grid gap-6 md:grid-cols-4">
                            <!-- Kota Asal -->
                            <div>
                                <label for="origin_city" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Kota Asal
                                </label>
                                <div class="relative">
                                    <select
                                        name="origin_city"
                                        id="origin_city"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5 pl-11 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 appearance-none"
                                        required
                                    >
                                        <option value="">Pilih Kota Asal</option>
                                        <?php $__currentLoopData = $originCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($city); ?>" <?php if(old('origin_city') === $city): echo 'selected'; endif; ?>><?php echo e($city); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Kota Tujuan -->
                            <div>
                                <label for="destination_city" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Kota Tujuan
                                </label>
                                <div class="relative">
                                    <select
                                        name="destination_city"
                                        id="destination_city"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5 pl-11 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 appearance-none"
                                        required
                                    >
                                        <option value="">Pilih Kota Tujuan</option>
                                        <?php $__currentLoopData = $destinationCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($city); ?>" <?php if(old('destination_city') === $city): echo 'selected'; endif; ?>><?php echo e($city); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="departure_date" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Tanggal
                                </label>
                                <div class="relative">
                                    <input
                                        type="date"
                                        name="departure_date"
                                        id="departure_date"
                                        min="<?php echo e(now()->format('Y-m-d')); ?>"
                                        value="<?php echo e(old('departure_date', now()->format('Y-m-d'))); ?>"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3.5 pl-11 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:ring-4 hover:ring-blue-500/20">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        CARI TRIP
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tips/Info Section -->
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-gray-600 animate-fade-in-up" style="animation-delay: 0.3s">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <span>Notifikasi via WhatsApp</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span>Pastikan nomor benar</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span>Booking cepat & mudah</span>
                        </div>
                    </div>

                    <!-- Login/Register Buttons for Guest Users -->
                    <?php if(auth()->guard()->guest()): ?>
                        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.4s">
                            <p class="text-sm text-gray-600">Belum punya akun?</p>
                            <div class="flex items-center gap-3">
                                <a
                                    href="<?php echo e(route('login')); ?>"
                                    class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-full border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 flex items-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Login
                                </a>
                                <a
                                    href="<?php echo e(route('register')); ?>"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 hover:ring-4 hover:ring-blue-500/20"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Daftar
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Recommended Trips Section -->
        <?php if($trips->count() > 0): ?>
            <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-10 animate-fade-in-up" style="animation-delay: 0.4s">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900">Rekomendasi Perjalanan</h2>
                    </div>
                    <p class="text-gray-600 mt-2 ml-16">Trip terpilih untuk perjalananmu</p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 items-stretch">
                    <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="animate-fade-in-up" style="animation-delay: <?php echo e(0.5 + ($index * 0.1)); ?>s">
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
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>
    </div>

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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/home.blade.php ENDPATH**/ ?>