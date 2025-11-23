<section>
    <!-- Verification Form (separate) -->
    <form id="send-verification" method="post" action="<?php echo e(route('verification.send')); ?>" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>

    <!-- Profile Update Form -->
    <form method="post" action="<?php echo e(route('profile.update')); ?>" class="space-y-6" id="profile-update-form" onsubmit="return handleProfileSubmit(event)">
        <?php echo csrf_field(); ?>
        <?php echo method_field('patch'); ?>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                Nama Lengkap
            </label>
            <input
                id="name"
                name="name"
                type="text"
                value="<?php echo e(old('name', $user->name)); ?>"
                required
                autofocus
                autocomplete="name"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 transition <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            >
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('name'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                Email
            </label>
            <input
                id="email"
                name="email"
                type="email"
                value="<?php echo e(old('email', $user->email)); ?>"
                required
                autocomplete="username"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 transition <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            >
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('email')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>

            <?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        <?php echo e(__('Your email address is unverified.')); ?>

                        <button type="button" onclick="document.getElementById('send-verification').submit();" class="underline text-sm text-sky-600 hover:text-sky-700">
                            <?php echo e(__('Click here to re-send the verification email.')); ?>

                        </button>
                    </p>
                    <?php if(session('status') === 'verification-link-sent'): ?>
                        <p class="mt-2 font-medium text-sm text-emerald-600">
                            <?php echo e(__('A new verification link has been sent to your email address.')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                Nomor WhatsApp <span class="text-red-500">*</span>
            </label>
            <input
                id="phone"
                name="phone"
                type="tel"
                value="<?php echo e(old('phone', $user->phone)); ?>"
                required
                autocomplete="tel"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 transition <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                placeholder="081234567890"
            >
            <p class="mt-1 text-xs text-gray-500">Nomor ini akan digunakan untuk menerima notifikasi WhatsApp</p>
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['class' => 'mt-2','messages' => $errors->get('phone')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-2','messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('phone'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <button
                type="submit"
                class="px-6 py-3 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 disabled:opacity-50 disabled:cursor-not-allowed"
                id="save-profile-btn"
            >
                Simpan Perubahan
            </button>

            <?php if(session('status') === 'profile-updated'): ?>
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-semibold text-emerald-600"
                >
                    ✓ Tersimpan
                </p>
            <?php endif; ?>
        </div>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm font-semibold text-red-800 mb-2">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                <p class="text-sm font-semibold text-emerald-800"><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>
    </form>

    <script>
        function handleProfileSubmit(event) {
            const form = event.target;
            const submitBtn = document.getElementById('save-profile-btn');

            // Disable button saat submit
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';
            }

            // Form akan submit secara normal
            return true;
        }

        // Re-enable button jika form gagal submit (misalnya karena validasi)
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profile-update-form');
            if (form) {
                form.addEventListener('invalid', function(event) {
                    const submitBtn = document.getElementById('save-profile-btn');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Simpan Perubahan';
                    }
                }, true);
            }
        });
    </script>
</section>
<?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/profile/partials/update-profile-information-form.blade.php ENDPATH**/ ?>