@props(['name', 'title' => '', 'show' => false])

<div
    x-data="{
        show: @js($show),
        open() { this.show = true; document.body.style.overflow = 'hidden'; },
        close() { this.show = false; document.body.style.overflow = ''; }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    style="display: none;"
    @click.self="close()"
    @keydown.escape.window="close()"
>
    <div
        class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-2xl bg-white"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
    >
        @if($title)
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">{{ $title }}</h3>
                <button @click="close()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{ $slot }}
    </div>
</div>

