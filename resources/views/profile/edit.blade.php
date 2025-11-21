<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Warning Alert -->
            @if(!auth()->user()->phone)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-xl shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-yellow-800">
                                Peringatan!
                            </p>
                            <p class="mt-1 text-sm text-yellow-700">
                                Nomor telepon Anda belum diisi. Silakan lengkapi nomor telepon untuk menerima notifikasi WhatsApp.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profile Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-sky-600 to-indigo-600 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Informasi Profile</h3>
                    <p class="text-sm text-sky-100 mt-1">Update informasi akun dan email Anda</p>
                </div>

                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Update Password</h3>
                    <p class="text-sm text-gray-300 mt-1">Pastikan akun Anda menggunakan password yang kuat dan aman</p>
                </div>

                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
                <div class="bg-red-50 px-6 py-4 border-b border-red-200">
                    <h3 class="text-lg font-semibold text-red-900">Hapus Akun</h3>
                    <p class="text-sm text-red-700 mt-1">Hapus akun Anda secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                </div>

                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
