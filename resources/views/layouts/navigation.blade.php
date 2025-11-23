<!-- resources/views/layouts/navigation.blade.php -->
<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = window.scrollY > 10"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     :class="scrolled ? 'bg-white/80 backdrop-blur-md shadow-lg shadow-blue-100/20' : 'bg-white/60 backdrop-blur-sm'">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="group">
                        <span class="font-bold text-2xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent transition-all duration-300 group-hover:from-blue-700 group-hover:to-indigo-700">SIBUSKU</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Beranda') }}
                    </x-nav-link>
                    
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        
                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.buses.index')" :active="request()->routeIs('admin.buses.*')">
                                {{ __('Bus') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.routes.index')" :active="request()->routeIs('admin.routes.*')">
                                {{ __('Rute') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.trips.index')" :active="request()->routeIs('admin.trips.*')">
                                {{ __('Trip') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings.*')">
                                {{ __('Booking') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.whatsapp-logs.index')" :active="request()->routeIs('admin.whatsapp-logs.*')">
                                {{ __('Log WA') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('user.bookings.index')" :active="request()->routeIs('user.bookings.*')">
                                {{ __('Riwayat Booking') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-white/50 hover:bg-white/80 backdrop-blur-sm border border-gray-200/50 hover:border-gray-300 transition-all duration-200 ease-in-out hover:shadow-md">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-semibold text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-white/50 backdrop-blur-sm transition-all duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/90 backdrop-blur-md border-t border-gray-200/50">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.buses.index')" :active="request()->routeIs('admin.buses.*')">
                        {{ __('Bus') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.routes.index')" :active="request()->routeIs('admin.routes.*')">
                        {{ __('Rute') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.trips.index')" :active="request()->routeIs('admin.trips.*')">
                        {{ __('Trip') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings.*')">
                        {{ __('Booking') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.whatsapp-logs.index')" :active="request()->routeIs('admin.whatsapp-logs.*')">
                        {{ __('Log WA') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('user.bookings.index')" :active="request()->routeIs('user.bookings.*')">
                        {{ __('Riwayat Booking') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>