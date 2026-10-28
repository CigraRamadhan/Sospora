@props([
    'name' => 'confirm-modal',
    'title' => 'Konfirmasi Tindakan',
    'description' => 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    'confirmText' => 'Ya, Lanjutkan',
    'confirmVariant' => 'danger', // danger, primary, success
])

@php
$buttonClass = match ($confirmVariant) {
    'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
    default => 'bg-primary-600 hover:bg-primary-700 text-white focus:ring-primary-500',
};
@endphp

<div
    x-data="{
        open: false,
        actionUrl: '',
        title: '{{ $title }}',
        description: '{{ $description }}',
        method: 'POST',
        showNote: false,
        noteLabel: 'Catatan (Opsional)',
        noteName: 'catatan_admin',
        notePlaceholder: 'Tambahkan catatan jika diperlukan...',
        openModal(detail) {
            this.open = true;
            if (detail) {
                if (detail.action) this.actionUrl = detail.action;
                if (detail.title) this.title = detail.title;
                if (detail.description) this.description = detail.description;
                if (detail.method) this.method = detail.method;
                this.showNote = !!detail.showNote;
                if (detail.noteLabel) this.noteLabel = detail.noteLabel;
            }
        },
        closeModal() {
            this.open = false;
        }
    }"
    x-on:open-confirm-modal.window="if ($event.detail.name === '{{ $name }}' || !$event.detail.name) openModal($event.detail)"
    x-on:keydown.escape.window="closeModal()"
    x-show="open"
    class="relative z-50"
    style="display: none;"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
        x-on:click="closeModal()"
    ></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto p-4 sm:p-6 md:p-20 flex items-center justify-center">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
            x-on:click.stop
        >
            <form :action="actionUrl" method="POST">
                @csrf
                <template x-if="method === 'DELETE'">
                    @method('DELETE')
                </template>
                <template x-if="method === 'PATCH'">
                    @method('PATCH')
                </template>
                <template x-if="method === 'PUT'">
                    @method('PUT')
                </template>

                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl {{ $confirmVariant === 'danger' ? 'bg-red-100 text-red-600' : ($confirmVariant === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-primary-100 text-primary-600') }}">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-heading font-semibold text-slate-900" x-text="title"></h3>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed" x-text="description"></p>

                            <!-- Optional Note Input -->
                            <div x-show="showNote" class="mt-4">
                                <label :for="noteName" class="block text-xs font-medium text-slate-700 mb-1" x-text="noteLabel"></label>
                                <textarea
                                    :name="noteName"
                                    :id="noteName"
                                    rows="3"
                                    :placeholder="notePlaceholder"
                                    class="w-full text-sm rounded-xl border border-slate-300 px-3 py-2 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 border-t border-slate-100">
                    <button
                        type="button"
                        x-on:click="closeModal()"
                        class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 bg-white hover:bg-slate-100 text-sm font-medium transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 cursor-pointer {{ $buttonClass }}"
                    >
                        {{ $confirmText }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
