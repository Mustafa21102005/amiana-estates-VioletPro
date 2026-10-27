<x-app-layout>
    <x-slot name="title">My Account</x-slot>

    <div class="space-y-6">
        <x-card>
            <div>
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-card>

        <x-card>
            <div>
                @include('profile.partials.update-password-form')
            </div>
        </x-card>
    </div>
</x-app-layout>
