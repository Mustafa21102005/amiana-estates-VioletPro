<x-app-layout>
    <x-slot name="title">Contact & Settings</x-slot>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <x-card>
            <h2 class="font-serif text-2xl text-navy dark:text-ivory">Website</h2>
            <div class="mt-4 h-px w-10 bg-gold"></div>
            <div class="mt-6">
                <x-form-field name="tagline" label="Tagline" :value="$settings['tagline'] ?? null"
                    hint="A short line shown under the logo and in the hero. Maximum 160 characters." />
            </div>
        </x-card>

        <x-card>
            <h2 class="font-serif text-2xl text-navy dark:text-ivory">Contact details</h2>
            <div class="mt-4 h-px w-10 bg-gold"></div>
            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                <x-form-field name="phone" label="Phone" :value="$settings['phone'] ?? null" />
                <x-form-field name="email" label="Email" type="email" :value="$settings['email'] ?? null" />
                <x-form-field name="address" label="Address" :textarea="true" :value="$settings['address'] ?? null"
                    class="sm:col-span-2" />
                <x-form-field name="map_url" label="Google Maps link" type="url" :value="$settings['map_url'] ?? null"
                    hint="Paste the full link, starting with https://" class="sm:col-span-2" />
            </div>
        </x-card>

        <x-card>
            <h2 class="font-serif text-2xl text-navy dark:text-ivory">Social media</h2>
            <div class="mt-4 h-px w-10 bg-gold"></div>
            <p class="mt-4 text-sm text-charcoal/70 dark:text-ivory/70">
                Leave a field empty to hide that icon on the website.
            </p>
            <div class="mt-6 space-y-6">
                <x-form-field name="facebook" label="Facebook" type="url" :value="$settings['facebook'] ?? null"
                    placeholder="https://facebook.com/..." />
                <x-form-field name="instagram" label="Instagram" type="url" :value="$settings['instagram'] ?? null"
                    placeholder="https://instagram.com/..." />
                <x-form-field name="whatsapp" label="WhatsApp" :value="$settings['whatsapp'] ?? null" hint="Include the country code." />
            </div>
        </x-card>

        <div class="flex justify-end">
            <x-primary-button>Save settings</x-primary-button>
        </div>
    </form>
</x-app-layout>
