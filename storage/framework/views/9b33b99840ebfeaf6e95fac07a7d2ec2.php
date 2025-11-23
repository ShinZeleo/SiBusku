<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Full-Screen Background with Image -->
        <div 
            class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('<?php echo e(asset('build/assets/Images/Back.png')); ?>'); filter: blur(2px);"
        >
            <!-- Darkened Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-indigo-900/70 to-gray-900/90"></div>
            
            <!-- Soft Bokeh Light Overlays -->
            <div class="absolute top-20 right-20 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-20 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Logo & Branding -->
            <div class="text-center mb-10 animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm border border-white/30 flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 16c0 .88.39 1.67 1 2.22V20a1 1 0 001 1h1a1 1 0 001-1v-1h8v1a1 1 0 001 1h1a1 1 0 001-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z"/>
                        </svg>
                    </div>
                    <h1 class="text-5xl font-bold text-white" style="font-family: 'Inter', 'SF Pro', 'Plus Jakarta Sans', sans-serif; letter-spacing: -1px;">
                        SiBusku
                    </h1>
                </div>
                <p class="text-white/80 text-sm">Solusi mudah untuk pesan tiket bus</p>
            </div>

            <!-- Glassmorphism Auth Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 sm:p-10 shadow-2xl border border-white/20 relative overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s">
                <!-- Spotlight Glow Behind Card -->
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600/20 via-indigo-600/20 to-purple-600/20 rounded-3xl blur-xl opacity-50"></div>
                
                <div class="relative">
                    <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <h2 class="text-3xl font-bold text-white mb-2">Selamat Datang</h2>
                        <p class="text-white/70 text-sm">Masuk ke akun SiBusku Anda</p>
                    </div>

                    <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                        <?php echo csrf_field(); ?>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-white/90 mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="<?php echo e(old('email')); ?>"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 backdrop-blur-sm pl-12 pr-4 py-3.5 text-white placeholder-white/50 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none transition-all duration-200 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 focus:border-red-500 focus:ring-red-500/30 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="nama@email.com"
                                >
                            </div>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('email'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email')),'class' => 'mt-2']); ?>
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

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-white/90 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full rounded-xl border border-white/20 bg-white/10 backdrop-blur-sm pl-12 pr-4 py-3.5 text-white placeholder-white/50 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none transition-all duration-200 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 focus:border-red-500 focus:ring-red-500/30 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Masukkan password Anda"
                                >
                            </div>
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('password'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password')),'class' => 'mt-2']); ?>
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

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input
                                        id="remember_me"
                                        type="checkbox"
                                        name="remember"
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-white/20 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                                </div>
                                <span class="ml-3 text-sm text-white/80 group-hover:text-white transition-colors">Ingat saya</span>
                            </label>

                            <?php if(Route::has('password.request')): ?>
                                <a
                                    href="<?php echo e(route('password.request')); ?>"
                                    class="text-sm font-semibold text-white/80 hover:text-white transition-colors"
                                >
                                    Lupa password?
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center gap-2 py-4 px-6 border border-transparent rounded-full text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-xl hover:shadow-2xl hover:scale-105 hover:ring-4 hover:ring-blue-500/20"
                        >
                            <span>Masuk</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/20"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-transparent text-white/60">atau</span>
                        </div>
                    </div>

                    <!-- Google Login Button -->
                    <button
                        type="button"
                        class="w-full flex items-center justify-center gap-3 py-3.5 px-4 border border-white/20 rounded-full text-base font-medium text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/30 transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span>Masuk dengan Google</span>
                    </button>

                    <!-- Register Link -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-white/70">
                            Belum punya akun?
                            <a 
                                href="<?php echo e(route('register')); ?>" 
                                class="font-semibold text-white hover:text-blue-300 transition-colors bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent"
                            >
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-white/60 mt-8 animate-fade-in-up" style="animation-delay: 0.3s">
                © <?php echo e(date('Y')); ?> SiBusku. All rights reserved.
            </p>
        </div>
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
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\.PUSAT\Project\Kuliah\S3\Pemrograman Web\Projek_Final\sibusku\resources\views/auth/login.blade.php ENDPATH**/ ?>