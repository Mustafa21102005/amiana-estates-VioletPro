<div x-data="{ action: '', title: 'Delete this item?', message: '' }"
    x-on:confirm-delete.window="
        action = $event.detail.action;
        title = $event.detail.title ?? 'Delete this item?';
        message = $event.detail.message ?? 'This cannot be undone.';
        $dispatch('open-modal', 'confirm-delete');
    ">
    <x-modal name="confirm-delete" maxWidth="sm">
        <form method="POST" x-bind:action="action" class="p-6 sm:p-8">
            @csrf
            @method('DELETE')

            <h2 class="font-serif text-2xl text-navy dark:text-ivory" x-text="title"></h2>
            <div class="mt-4 h-px w-10 bg-red-600"></div>
            <p class="mt-4 text-sm text-charcoal/70 dark:text-ivory/70" x-text="message"></p>

            <div class="mt-8 flex items-center justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-danger-button>Delete</x-danger-button>
            </div>
        </form>
    </x-modal>
</div>
