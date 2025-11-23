@props(['initialView' => 'login'])

<div 
    x-data="{ 
        currentView: '{{ $initialView }}',
        isAnimating: false,
        loginImage: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
        registerImage: 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2053&q=80',
        switchView(view) {
            if (this.currentView === view || this.isAnimating) return;
            this.isAnimating = true;
            setTimeout(() => {
                this.currentView = view;
                setTimeout(() => {
                    this.isAnimating = false;
                }, 50);
            }, 300);
        }
    }"
    class="min-h-screen flex overflow-hidden"
>
    <!-- Image Panel (Left initially, Right when register) -->
    <div 
        class="hidden lg:flex lg:w-1/2 relative bg-cover bg-center transition-transform duration-700 ease-in-out"
        :class="currentView === 'login' ? 'translate-x-0' : 'translate-x-full'"
        style="will-change: transform;"
    >
        <!-- Login Image -->
        <div 
            class="absolute inset-0 bg-cover bg-center transition-opacity duration-700 ease-in-out"
            :class="currentView === 'login' ? 'opacity-100 z-10' : 'opacity-0 z-0'"
            :style="`background-image: url('${loginImage}');`"
        ></div>
        
        <!-- Register Image -->
        <div 
            class="absolute inset-0 bg-cover bg-center transition-opacity duration-700 ease-in-out"
            :class="currentView === 'register' ? 'opacity-100 z-10' : 'opacity-0 z-0'"
            :style="`background-image: url('${registerImage}');`"
        ></div>
        
        <!-- Dark Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-blue-800/70 to-indigo-900/80 z-20"></div>
        
        <!-- Content Overlay -->
        <div class="relative z-30 flex flex-col justify-center items-center h-full px-12 text-white">
            <div class="max-w-md text-center">
                <div class="mb-8">
                    <div class="inline-flex h-20 w-20 bg-white/20 backdrop-blur-sm rounded-2xl items-center justify-center shadow-2xl mb-6">
                        <span class="text-3xl font-bold text-white">SIB</span>
                    </div>
                </div>
                <h1 
                    class="text-4xl font-bold mb-4 leading-tight transition-opacity duration-500"
                    :class="currentView === 'login' ? 'opacity-100' : 'opacity-0'"
                >
                    Selamat Datang Kembali
                </h1>
                <p 
                    class="text-xl text-blue-100 leading-relaxed transition-opacity duration-500"
                    :class="currentView === 'login' ? 'opacity-100' : 'opacity-0'"
                >
                    Kelola perjalanan Anda dengan mudah dan efisien
                </p>
                
                <h1 
                    class="text-4xl font-bold mb-4 leading-tight transition-opacity duration-500 absolute"
                    :class="currentView === 'register' ? 'opacity-100 relative' : 'opacity-0'"
                >
                    Mulai Perjalanan Anda
                </h1>
                <p 
                    class="text-xl text-blue-100 leading-relaxed transition-opacity duration-500"
                    :class="currentView === 'register' ? 'opacity-100' : 'opacity-0'"
                >
                    Daftar sekarang dan nikmati kemudahan booking tiket bus
                </p>
            </div>
        </div>
    </div>

    <!-- Form Panel (Right initially, Left when register) -->
    <div 
        class="w-full lg:w-1/2 flex items-center justify-center bg-white px-4 sm:px-6 lg:px-8 py-12 transition-transform duration-700 ease-in-out"
        :class="currentView === 'login' ? 'translate-x-0' : '-translate-x-full'"
        style="will-change: transform;"
    >
        <div class="w-full max-w-md space-y-8">
            <!-- Logo & Header (Mobile) -->
            <div class="text-center lg:hidden">
                <div class="mx-auto h-16 w-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                    <span class="text-2xl font-bold text-white">SIB</span>
                </div>
                <h2 
                    class="text-3xl font-bold text-gray-900 transition-opacity duration-300"
                    :class="currentView === 'login' ? 'opacity-100' : 'opacity-0 hidden'"
                >
                    Selamat Datang
                </h2>
                <p 
                    class="mt-2 text-sm text-gray-600 transition-opacity duration-300"
                    :class="currentView === 'login' ? 'opacity-100' : 'opacity-0 hidden'"
                >
                    Masuk ke akun SIBUSKU Anda
                </p>
                
                <h2 
                    class="text-3xl font-bold text-gray-900 transition-opacity duration-300"
                    :class="currentView === 'register' ? 'opacity-100' : 'opacity-0 hidden'"
                >
                    Buat Akun Baru
                </h2>
                <p 
                    class="mt-2 text-sm text-gray-600 transition-opacity duration-300"
                    :class="currentView === 'register' ? 'opacity-100' : 'opacity-0 hidden'"
                >
                    Daftar untuk mulai booking tiket bus
                </p>
            </div>

            <!-- Desktop Header -->
            <div class="hidden lg:block">
                <h2 
                    class="text-3xl font-bold text-gray-900 mb-2 transition-opacity duration-300"
                    :class="currentView === 'login' ? 'opacity-100' : 'opacity-0 hidden'"
                >
                    Selamat Datang
                </h2>
                <p 
                    class="text-sm text-gray-600 transition-opacity duration-300"
                    :class="currentView === 'login' ? 'opacity-100' : 'opacity-0 hidden'"
                >
                    Masuk ke akun SIBUSKU Anda
                </p>
                
                <h2 
                    class="text-3xl font-bold text-gray-900 mb-2 transition-opacity duration-300"
                    :class="currentView === 'register' ? 'opacity-100' : 'opacity-0 hidden'"
                >
                    Buat Akun Baru
                </h2>
                <p 
                    class="text-sm text-gray-600 transition-opacity duration-300"
                    :class="currentView === 'register' ? 'opacity-100' : 'opacity-0 hidden'"
                >
                    Daftar untuk mulai booking tiket bus
                </p>
            </div>

            <!-- Login Form -->
            <div 
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-opacity duration-300"
                :class="currentView === 'login' ? 'opacity-100' : 'opacity-0 absolute pointer-events-none'"
                style="width: calc(100% - 2rem);"
            >
                {{ $loginForm }}
            </div>

            <!-- Register Form -->
            <div 
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 transition-opacity duration-300"
                :class="currentView === 'register' ? 'opacity-100' : 'opacity-0 absolute pointer-events-none'"
                style="width: calc(100% - 2rem);"
            >
                {{ $registerForm }}
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-500">
                © {{ date('Y') }} SIBUSKU. All rights reserved.
            </p>
        </div>
    </div>
</div>

