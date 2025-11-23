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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Detail Booking')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <a href="<?php echo e(route('user.bookings.index')); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium mb-4 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Riwayat Booking
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-8">
                    <!-- Booking Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 pb-6 border-b border-gray-200">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-gray-900">Detail Booking</h1>
                                <?php
                                    if ($booking->status === 'confirmed') {
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($booking->status === 'pending') {
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($booking->status === 'cancelled') {
                                        $statusClass = 'bg-red-100 text-red-800';
                                    } elseif ($booking->status === 'completed') {
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                    } else {
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                    }
                                ?>
                                <span class="px-4 py-1.5 inline-flex text-sm font-semibold rounded-full <?php echo e($statusClass); ?>">
                                    <?php echo e($booking->status_in_indonesian); ?>

                                </span>
                            </div>
                            <p class="text-gray-600">
                                <span class="font-semibold">Kode Booking:</span>
                                <span class="font-mono text-blue-600">SIB-<?php echo e(str_pad($booking->id, 4, '0', STR_PAD_LEFT)); ?></span>
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Dibuat pada <?php echo e(\Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i')); ?>

                            </p>
                        </div>
                    </div>

                    <!-- Information Cards Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Informasi Trip -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                <h2 class="text-lg font-bold text-gray-900">Informasi Trip</h2>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Rute</p>
                                    <p class="text-lg font-bold text-gray-900">
                                        <?php echo e($booking->trip->route->origin_city); ?>

                                        <span class="text-blue-600 mx-2">→</span>
                                        <?php echo e($booking->trip->route->destination_city); ?>

                                    </p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggal</p>
                                        <p class="font-semibold text-gray-900"><?php echo e(\Carbon\Carbon::parse($booking->trip->departure_date)->format('d M Y')); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Jam</p>
                                        <p class="font-semibold text-gray-900"><?php echo e(\Carbon\Carbon::parse($booking->trip->departure_time)->format('H:i')); ?></p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Durasi</p>
                                        <p class="font-semibold text-gray-900"><?php echo e($booking->trip->route->duration_estimate); ?> jam</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Kelas</p>
                                        <p class="font-semibold text-gray-900"><?php echo e($booking->trip->bus->bus_class); ?></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama Bus</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($booking->trip->bus->name); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Booking -->
                        <div class="bg-gradient-to-br from-gray-50 to-slate-50 p-6 rounded-xl border border-gray-200">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h2 class="text-lg font-bold text-gray-900">Informasi Booking</h2>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nama Pemesan</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($booking->customer_name); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">No. HP</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($booking->customer_phone); ?></p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Kursi yang Dipilih</p>
                                    <p class="font-semibold text-gray-900">
                                        <?php if($booking->selected_seats): ?>
                                            <?php echo e($booking->selected_seats); ?>

                                        <?php else: ?>
                                            <?php echo e($booking->seats_count); ?> kursi
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Harga/Kursi</p>
                                        <p class="font-semibold text-gray-900"><?php echo e($booking->trip->price_formatted); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Total Harga</p>
                                        <p class="font-bold text-xl text-blue-600"><?php echo e($booking->total_price_formatted); ?></p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status Pembayaran</p>
                                        <?php
                                            if ($booking->payment_status === 'paid') {
                                                $paymentClass = 'bg-green-100 text-green-800';
                                                $paymentLabel = 'Lunas';
                                            } elseif ($booking->payment_status === 'pending') {
                                                $paymentClass = 'bg-yellow-100 text-yellow-800';
                                                $paymentLabel = 'Menunggu';
                                            } else {
                                                $paymentClass = 'bg-red-100 text-red-800';
                                                $paymentLabel = 'Gagal';
                                            }
                                        ?>
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full <?php echo e($paymentClass); ?>">
                                            <?php echo e($paymentLabel); ?>

                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status WhatsApp</p>
                                        <?php
                                            $waBadge = booking_whatsapp_badge($booking);
                                        ?>
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold <?php echo e($waBadge['classes']); ?>">
                                            <span class="h-2 w-2 rounded-full <?php echo e($waBadge['dot']); ?>"></span>
                                            <?php echo e($waBadge['label']); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Booking Timeline -->
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Status Booking</h2>
                        <div class="relative">
                            <!-- Progress Line -->
                            <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200">
                                <div class="h-full bg-blue-600 transition-all duration-500" style="width: <?php echo e($progressWidth ?? '0%'); ?>"></div>
                            </div>

                            <div class="relative flex justify-between">
                                <!-- Step 1: Dipesan -->
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full <?php echo e($booking->status !== 'cancelled' ? 'bg-blue-600' : 'bg-gray-400'); ?> flex items-center justify-center z-10 shadow-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <p class="text-sm font-semibold <?php echo e($booking->status !== 'cancelled' ? 'text-blue-600' : 'text-gray-500'); ?>">Dipesan</p>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e(\Carbon\Carbon::parse($booking->created_at)->format('d M Y')); ?></p>
                                    </div>
                                </div>

                                <!-- Step 2: Dikonfirmasi -->
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full <?php echo e(in_array($booking->status, ['confirmed', 'completed']) ? 'bg-blue-600' : 'bg-gray-400'); ?> flex items-center justify-center z-10 shadow-lg">
                                        <?php if(in_array($booking->status, ['confirmed', 'completed'])): ?>
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        <?php else: ?>
                                            <span class="text-white text-sm font-bold">2</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <p class="text-sm font-semibold <?php echo e(in_array($booking->status, ['confirmed', 'completed']) ? 'text-blue-600' : 'text-gray-500'); ?>">Dikonfirmasi</p>
                                        <?php if($booking->status === 'confirmed' || $booking->status === 'completed'): ?>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <?php
                                                    $confirmedLog = $booking->statusLogs->where('status', 'confirmed')->first();
                                                ?>
                                                <?php if($confirmedLog): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($confirmedLog->created_at)->format('d M Y')); ?>

                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Step 3: Selesai -->
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full <?php echo e($booking->status === 'completed' ? 'bg-blue-600' : 'bg-gray-400'); ?> flex items-center justify-center z-10 shadow-lg">
                                        <?php if($booking->status === 'completed'): ?>
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        <?php else: ?>
                                            <span class="text-white text-sm font-bold">3</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <p class="text-sm font-semibold <?php echo e($booking->status === 'completed' ? 'text-blue-600' : 'text-gray-500'); ?>">Selesai</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Log WhatsApp -->
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Log WhatsApp Terbaru</h2>
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <?php if($booking->latestWhatsappLog): ?>
                                <?php
                                    $waLog = $booking->latestWhatsappLog;

                                    $statusMap = [
                                        'sent' => [
                                            'label' => 'Terkirim',
                                            'class' => 'bg-emerald-100 text-emerald-800',
                                            'icon'  => 'check-circle',
                                        ],
                                        'pending' => [
                                            'label' => 'Menunggu',
                                            'class' => 'bg-yellow-100 text-yellow-800',
                                            'icon'  => 'clock',
                                        ],
                                        'failed' => [
                                            'label' => 'Gagal',
                                            'class' => 'bg-red-100 text-red-800',
                                            'icon'  => 'x-circle',
                                        ],
                                    ];

                                    $status = $waLog->status ?? 'unknown';

                                    $statusInfo = $statusMap[$status] ?? [
                                        'label' => ucfirst($status),
                                        'class' => 'bg-gray-100 text-gray-800',
                                        'icon'  => 'info',
                                    ];
                                ?>

                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 pb-4 border-b border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 rounded-lg <?php echo e($statusInfo['class']); ?>">
                                            <?php if($statusInfo['icon'] === 'check-circle'): ?>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            <?php elseif($statusInfo['icon'] === 'clock'): ?>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status Notifikasi</p>
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full <?php echo e($statusInfo['class']); ?>">
                                                <?php echo e($statusInfo['label']); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">
                                            <?php echo e($waLog->sent_at ? 'Dikirim pada' : 'Dibuat pada'); ?>

                                        </p>
                                        <p class="text-sm font-semibold text-gray-900">
                                            <?php echo e(\Carbon\Carbon::parse($waLog->sent_at ?? $waLog->created_at)->format('d M Y, H:i')); ?>

                                        </p>
                                    </div>
                                </div>

                                <?php if($waLog->message): ?>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Pesan</p>
                                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed"><?php echo e($waLog->message); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if($waLog->error_message): ?>
                                    <div class="mt-4">
                                        <p class="text-xs font-semibold text-red-600 uppercase tracking-wide mb-2">Error Message</p>
                                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                            <p class="text-sm text-red-700"><?php echo e($waLog->error_message); ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada log WhatsApp untuk booking ini</p>
                                    <p class="text-xs text-gray-400 mt-1">Notifikasi akan muncul setelah dikirim</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="<?php echo e(route('user.bookings.index')); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-colors shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Kembali ke Riwayat
                        </a>
                        <?php if($booking->status === 'confirmed' || $booking->status === 'completed'): ?>
                            <a href="<?php echo e(route('bookings.ticket', $booking->id)); ?>" target="_blank" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download E-Ticket
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
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
<?php endif; ?>
<?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/user/bookings/show.blade.php ENDPATH**/ ?>