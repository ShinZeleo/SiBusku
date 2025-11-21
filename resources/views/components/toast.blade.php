@props(['type' => 'success', 'message' => '', 'duration' => 5000])

<div
    x-data="{ show: false, message: '', type: 'success' }"
    x-init="
        $watch('$store.toast.show', value => {
            if (value) {
                show = true;
                message = $store.toast.message;
                type = $store.toast.type || 'success';
                setTimeout(() => { show = false; $store.toast.show = false; }, {{ $duration }});
            }
        });
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-full"
    class="fixed top-4 right-4 z-50 max-w-sm w-full"
    style="display: none;"
>
    <div
        :class="{
            'bg-emerald-50 border-emerald-200 text-emerald-800': type === 'success',
            'bg-red-50 border-red-200 text-red-800': type === 'error',
            'bg-yellow-50 border-yellow-200 text-yellow-800': type === 'warning',
            'bg-blue-50 border-blue-200 text-blue-800': type === 'info'
        }"
        class="border rounded-xl p-4 shadow-lg flex items-start gap-3"
    >
        <div
            :class="{
                'text-emerald-600': type === 'success',
                'text-red-600': type === 'error',
                'text-yellow-600': type === 'warning',
                'text-blue-600': type === 'info'
            }"
        >
            <svg x-show="type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <svg x-show="type === 'error'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <svg x-show="type === 'warning'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <svg x-show="type === 'info'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-semibold" x-text="message"></p>
        </div>
        <button
            @click="show = false; $store.toast.show = false"
            class="text-gray-400 hover:text-gray-600"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

