<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="min-h-screen">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main class="py-8">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <?php ($successMessage = session('success')); ?>

                    <?php if($successMessage): ?>
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition.opacity.duration.300ms
                            x-init="setTimeout(() => show = false, 6000)"
                            class="mb-6 rounded-2xl border border-green-200 bg-white shadow"
                        >
                            <div class="flex items-start gap-4 px-6 py-5">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-green-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900">Berhasil</p>
                                    <p class="text-sm text-slate-600"><?php echo e($successMessage); ?></p>
                                </div>
                                <button type="button" class="text-slate-400 transition hover:text-slate-600" @click="show = false">
                                    <span class="sr-only">Tutup notifikasi</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 8.586l4.95-4.95 1.414 1.414L11.414 10l4.95 4.95-1.414 1.414L10 11.414l-4.95 4.95-1.414-1.414L8.586 10 3.636 5.05l1.414-1.414L10 8.586z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php echo e($slot); ?>

                </div>
            </main>
        </div>

        <!-- Toast Notification -->
        <?php if (isset($component)) { $__componentOriginal7cfab914afdd05940201ca0b2cbc009b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7cfab914afdd05940201ca0b2cbc009b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.toast','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7cfab914afdd05940201ca0b2cbc009b)): ?>
<?php $attributes = $__attributesOriginal7cfab914afdd05940201ca0b2cbc009b; ?>
<?php unset($__attributesOriginal7cfab914afdd05940201ca0b2cbc009b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7cfab914afdd05940201ca0b2cbc009b)): ?>
<?php $component = $__componentOriginal7cfab914afdd05940201ca0b2cbc009b; ?>
<?php unset($__componentOriginal7cfab914afdd05940201ca0b2cbc009b); ?>
<?php endif; ?>

        <!-- Alpine Store untuk Toast -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if(session('success')): ?>
                    <?php if(str_contains(strtolower(session('success')), 'whatsapp')): ?>
                        Alpine.store('toast').showToast('<?php echo e(session('success')); ?>', 'success');
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    Alpine.store('toast').showToast('<?php echo e(session('error')); ?>', 'error');
                <?php endif; ?>
            });
        </script>
    </body>
</html>
<?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/layouts/app.blade.php ENDPATH**/ ?>