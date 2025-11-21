<section>
    <!-- Verification Form (separate) -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>

    <!-- Profile Update Form -->
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" id="profile-update-form" onsubmit="return handleProfileSubmit(event)">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                Nama Lengkap
            </label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 transition @error('name') border-red-400 @enderror"
            >
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
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
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 transition @error('email') border-red-400 @enderror"
            >
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Your email address is unverified.') }}
                        <button type="button" onclick="document.getElementById('send-verification').submit();" class="underline text-sm text-sky-600 hover:text-sky-700">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
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
                value="{{ old('phone', $user->phone) }}"
                required
                autocomplete="tel"
                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/40 transition @error('phone') border-red-400 @enderror"
                placeholder="081234567890"
            >
            <p class="mt-1 text-xs text-gray-500">Nomor ini akan digunakan untuk menerima notifikasi WhatsApp</p>
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
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

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-semibold text-emerald-600"
                >
                    ✓ Tersimpan
                </p>
            @endif
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-sm font-semibold text-red-800 mb-2">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif
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
