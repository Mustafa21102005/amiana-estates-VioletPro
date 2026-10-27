<section id="contact" class="scroll-mt-20 border-t border-mist/20 bg-ivory py-24 dark:border-white/10 dark:bg-navy">
    <div class="mx-auto grid max-w-7xl gap-14 px-6 lg:grid-cols-2 lg:gap-20 lg:px-8">

        <div class="max-w-xl">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-gold">Enquiries</p>
            <h2 class="mt-3 font-serif text-4xl text-navy sm:text-5xl dark:text-ivory">Arrange a viewing</h2>
            <div class="mt-5 h-px w-16 bg-gold"></div>
            <p class="mt-6 text-charcoal/80 dark:text-ivory/75">
                Leave your number and a few words about what you are looking for, and our team will call you back.
            </p>
        </div>

        <div>
            @if (session('contact_sent'))
                <div role="status" class="border border-gold/60 bg-gold/10 p-8">
                    <h3 class="font-serif text-2xl text-navy dark:text-ivory">Thank you</h3>
                    <p class="mt-3 text-sm text-charcoal/80 dark:text-ivory/75">
                        Your message has been sent. We will be in touch shortly.
                    </p>
                </div>
            @else
                <x-card>
                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6" x-data="{ busy: false }"
                        x-on:submit="busy = true">
                        @csrf

                        <div>
                            <x-input-label for="contact-phone" value="Phone" />
                            <x-text-input id="contact-phone" type="tel" name="phone" :value="old('phone')" required
                                autocomplete="tel" inputmode="tel" placeholder="+232 76 123 456"
                                pattern="\+[0-9\s\-().]+"
                                title="Start with your country code, for example +232 76 123 456"
                                class="mt-2 block w-full" />
                            <p class="mt-2 text-xs text-charcoal/60 dark:text-ivory/60">
                                Include your country code, starting with +. Leave out the 0 at the start of your local
                                number.
                            </p>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="contact-email" value="Email (optional)" />
                            <x-text-input id="contact-email" type="email" name="email" :value="old('email')"
                                autocomplete="email" class="mt-2 block w-full" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="contact-body" value="Message" />
                            <x-textarea id="contact-body" name="body" rows="5" required maxlength="2000"
                                class="mt-2 block w-full">{{ old('body') }}</x-textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        {{-- Honeypot field --}}
                        <div class="absolute -left-[9999px]" aria-hidden="true">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <x-primary-button class="w-full" x-bind:disabled="busy">
                            <span x-text="busy ? 'Sending…' : 'Send message'">Send message</span>
                        </x-primary-button>
                    </form>
                </x-card>
            @endif
        </div>
    </div>
</section>
