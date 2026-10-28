<div
    x-data="{
        toasts: [],
        addToast(message, type = 'success') {
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.removeToast(id), 4000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-init="
        @if(session('success'))
            addToast(@js(session('success')), 'success');
        @endif
        @if(session('error'))
            addToast(@js(session('error')), 'error');
        @endif
        @if(session('info'))
            addToast(@js(session('info')), 'info');
        @endif
        @if($errors->any())
            addToast(@js($errors->first()), 'error');
        @endif
    "
    x-on:toast.window="addToast($event.detail.message, $event.detail.type || 'success')"
    class="fixed top-4 right-4 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-4"
            class="pointer-events-auto flex items-start gap-3 p-4 rounded-xl shadow-lg border backdrop-blur-md text-sm transition-all"
            :class="{
                'bg-emerald-50/95 border-emerald-200 text-emerald-800': toast.type === 'success',
                'bg-red-50/95 border-red-200 text-red-800': toast.type === 'error',
                'bg-blue-50/95 border-blue-200 text-blue-800': toast.type === 'info',
                'bg-amber-50/95 border-amber-200 text-amber-800': toast.type === 'warning'
            }"
        >
            <!-- Icon -->
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </template>
                <template x-if="toast.type === 'info'">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </template>
            </div>

            <!-- Message -->
            <div class="flex-1 font-medium leading-tight" x-text="toast.message"></div>

            <!-- Close Button -->
            <button
                type="button"
                x-on:click="removeToast(toast.id)"
                class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
