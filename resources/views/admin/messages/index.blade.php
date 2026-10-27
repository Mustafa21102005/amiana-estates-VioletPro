<x-app-layout>
    <x-slot name="title">Messages</x-slot>

    <p class="max-w-2xl text-sm text-charcoal/70 dark:text-ivory/70">
        Enquiries sent from the contact form on the website, newest first.
    </p>

    <div x-data class="mt-6 overflow-x-auto bg-white ring-1 ring-mist/20 dark:bg-white/5 dark:ring-white/10">
        @if ($messages->isEmpty())
            <p class="px-6 py-12 text-center text-sm text-charcoal/60 dark:text-ivory/60">
                No messages yet. Enquiries from the website's contact form will appear here.
            </p>
        @else
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-mist/20 text-xs uppercase tracking-widest text-mist dark:border-white/10">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Received</th>
                        <th class="px-6 py-4 font-semibold">Phone</th>
                        <th class="hidden px-6 py-4 font-semibold md:table-cell">Email</th>
                        <th class="hidden px-6 py-4 font-semibold lg:table-cell">Message</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-mist/10 dark:divide-white/10">
                    @foreach ($messages as $message)
                        <tr @class(['font-semibold' => !$message->isRead()])>
                            <td class="whitespace-nowrap px-6 py-4">
                                @unless ($message->isRead())
                                    <span
                                        class="me-2 border border-gold px-2 py-0.5 text-[0.65rem] uppercase tracking-widest text-gold">New</span>
                                @endunless
                                <span title="{{ $message->created_at->format('j M Y, H:i') }}">
                                    {{ $message->created_at->diffForHumans() }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <a href="{{ $message->phoneLink() }}"
                                    class="transition hover:text-gold">{{ $message->phone }}</a>
                            </td>
                            <td class="hidden px-6 py-4 md:table-cell">{{ $message->email ?? '—' }}</td>
                            <td
                                class="hidden max-w-sm px-6 py-4 font-normal text-charcoal/70 lg:table-cell dark:text-ivory/70">
                                {{ \Illuminate\Support\Str::limit($message->body, 70) }}
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    class="flex items-center justify-end gap-5 text-xs font-semibold uppercase tracking-[0.18em]">
                                    <a href="{{ route('admin.messages.show', $message) }}"
                                        class="text-navy transition hover:text-gold dark:text-ivory">View</a>

                                    <button type="button"
                                        x-on:click="$dispatch('confirm-delete', {
                                            action: @js(route('admin.messages.destroy', $message)),
                                            title: 'Delete message?',
                                            message: 'This message will be permanently removed.',
                                        })"
                                        class="text-red-600 transition hover:text-red-400">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-6">{{ $messages->links() }}</div>
</x-app-layout>
