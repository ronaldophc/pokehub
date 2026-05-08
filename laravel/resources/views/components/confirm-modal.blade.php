<div
    x-data="{
        open: false,
        message: '',
        type: 'danger',
        action: null,
        show(e) {
            this.message = e.detail.message
            this.type    = e.detail.type || 'danger'
            this.action  = e.detail.action
            this.open    = true
        },
        confirm() {
            if (this.action) this.action()
            this.open = false
        },
        cancel() { this.open = false }
    }"
    x-on:open-confirm.window="show($event)"
    x-on:keydown.escape.window="cancel()"
    x-show="open"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-center px-4 pb-6 sm:pb-0"
    style="display: none"
>
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-zinc-900/60" x-on:click="cancel()"></div>

    {{-- Dialog --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm"
    >
        <div class="px-6 pt-6 pb-5">
            <p class="text-sm text-zinc-700 leading-relaxed" x-text="message"></p>
        </div>
        <div class="flex gap-2 px-6 pb-5 justify-end">
            <button
                x-on:click="cancel()"
                class="px-4 py-2 text-sm text-zinc-500 hover:bg-zinc-100 rounded-lg transition-colors font-medium"
            >
                Cancelar
            </button>
            <button
                x-on:click="confirm()"
                x-bind:class="{
                    'bg-red-600 hover:bg-red-700 text-white':    type === 'danger',
                    'bg-amber-500 hover:bg-amber-600 text-white': type === 'warning',
                    'bg-violet-600 hover:bg-violet-700 text-white': type === 'info',
                }"
                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors"
            >
                Confirmar
            </button>
        </div>
    </div>
</div>
