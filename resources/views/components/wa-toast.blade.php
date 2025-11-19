@props(['message' => 'WhatsApp terkirim'])

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition.opacity.duration.200ms
    x-init="setTimeout(() => show = false, 4000)"
    class="fixed bottom-6 right-6 z-50"
>
    <div class="flex max-w-sm items-center gap-3 rounded-2xl border border-green-200 bg-white px-4 py-3 text-sm shadow-2xl">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-500 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </span>
        <div>
            <p class="font-semibold text-slate-900">{{ $message }}</p>
            <p class="text-xs text-slate-500">Notifikasi berhasil dikirim ke pelanggan.</p>
        </div>
        <button type="button" class="text-slate-400 transition hover:text-slate-600" @click="show = false">
            <span class="sr-only">Tutup toast</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 8.586l4.95-4.95 1.414 1.414L11.414 10l4.95 4.95-1.414 1.414L10 11.414l-4.95 4.95-1.414-1.414L8.586 10 3.636 5.05l1.414-1.414L10 8.586z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
