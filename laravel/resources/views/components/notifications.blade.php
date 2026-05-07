<div
    x-data="{
        items: [],
        add(event) {
            const id = Date.now()
            this.items.push({ id, message: event.detail.message, type: event.detail.type ?? 'success' })
            setTimeout(() => this.remove(id), 3500)
        },
        remove(id) {
            this.items = this.items.filter(i => i.id !== id)
        }
    }"
    x-on:notify.window="add($event)"
    class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 w-80"
>
    <template x-for="item in items" :key="item.id">
        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            :class="{
                'bg-zinc-900 border-zinc-700': item.type === 'success',
                'bg-red-600 border-red-500': item.type === 'error',
            }"
            class="flex items-center justify-between gap-3 border rounded-lg px-4 py-3 shadow-lg"
        >
            <div class="flex items-center gap-2">
                <template x-if="item.type === 'success'">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </template>
                <template x-if="item.type === 'error'">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </template>
                <span class="text-sm text-white" x-text="item.message"></span>
            </div>
            <button @click="remove(item.id)" class="text-white/50 hover:text-white transition-colors shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>
