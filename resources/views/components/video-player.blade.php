@props(['videos'])

<div x-data="videoPlayer(@js($videos))" x-on:fullscreenchange.document="fullscreen = document.fullscreenElement === $refs.stage"
    {{ $attributes->class(['mx-auto w-full']) }}>

    {{-- Stage --}}
    <div x-ref="stage" tabindex="0" role="group" aria-label="Video player" x-on:mousemove="wake()"
        x-on:touchstart.passive="wake()" x-on:keydown="wake()" x-on:mouseleave="if (playing && ! overBar) idle = true"
        x-on:keydown.space.prevent.self="toggle()" x-on:keydown.arrow-right.prevent.self="seekBy(5)"
        x-on:keydown.arrow-left.prevent.self="seekBy(-5)" x-on:keydown.m.self="toggleMute()"
        x-on:keydown.f.self="toggleFullscreen()" :class="playing && !showControls ? 'cursor-none' : ''"
        class="relative aspect-video overflow-hidden bg-black shadow-2xl shadow-black/50 ring-1 ring-gold/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold">

        {{-- #t=0.1 makes the browser show a real frame as the poster --}}
        <video x-ref="video" :src="active.src + '#t=0.1'" preload="metadata" playsinline x-on:click="toggle()"
            x-on:loadedmetadata="onMetadata()" x-on:durationchange="onMetadata()" x-on:timeupdate="onTime()"
            x-on:play="onPlay()" x-on:pause="onPause()" x-on:ended="onPause()" x-on:waiting="buffering = true"
            x-on:canplay="buffering = false" x-on:playing="buffering = false" x-on:volumechange="syncVolume()"
            class="h-full w-full bg-black object-contain"></video>

        {{-- Loading spinner --}}
        <div x-show="buffering" x-cloak class="pointer-events-none absolute inset-0 flex items-center justify-center">
            <div class="h-12 w-12 animate-spin rounded-full border-2 border-gold/30 border-t-gold"></div>
        </div>

        {{-- Big play button --}}
        <button type="button" x-show="! playing && ! buffering" x-transition.opacity x-on:click="toggle()"
            aria-label="Play video"
            class="absolute inset-0 m-auto flex h-20 w-20 items-center justify-center rounded-full border border-gold bg-navy/50 text-gold backdrop-blur transition hover:bg-gold hover:text-navy focus:outline-none focus-visible:ring-2 focus-visible:ring-gold sm:h-24 sm:w-24">
            <svg class="h-8 w-8 sm:h-9 sm:w-9" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"
                stroke-width="1.5" stroke-linejoin="round" aria-hidden="true">
                <path d="M7.7 4.5v15L20.2 12 7.7 4.5Z" />
            </svg>
        </button>

        {{-- Control bar --}}
        <div x-on:mouseenter="overBar = true" x-on:mouseleave="overBar = false"
            :class="showControls ? 'opacity-100' : 'pointer-events-none opacity-0'"
            class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent px-4 pb-3 pt-12 transition-opacity duration-300 sm:px-6 sm:pb-4">

            <input type="range" min="0" max="1000" step="1" :value="position"
                x-on:input="seekTo($event.target.value)" x-on:keydown.arrow-left.prevent.stop="seekBy(-5)"
                x-on:keydown.arrow-right.prevent.stop="seekBy(5)" :style="`--fill: ${percent}%`"
                :aria-valuetext="format(current) + ' of ' + format(duration)" aria-label="Seek"
                class="player-range block w-full">

            <div class="mt-1 flex items-center justify-between gap-4 text-ivory">
                <div class="flex items-center gap-4">
                    <button type="button" x-on:click="toggle()" :aria-label="playing ? 'Pause' : 'Play'"
                        class="p-1.5 transition hover:text-gold focus:outline-none focus-visible:ring-2 focus-visible:ring-gold">
                        <svg x-show="! playing" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"
                            stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7.7 4.5v15L20.2 12 7.7 4.5Z" />
                        </svg>
                        <svg x-show="playing" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"
                            aria-hidden="true">
                            <rect x="6" y="5" width="4" height="14" rx="1" />
                            <rect x="14" y="5" width="4" height="14" rx="1" />
                        </svg>
                    </button>

                    <p class="text-xs tabular-nums text-ivory/80">
                        <span x-text="format(current)">0:00</span> / <span x-text="format(duration)">0:00</span>
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <button type="button" x-on:click="toggleMute()" :aria-label="muted ? 'Unmute' : 'Mute'"
                            class="p-1.5 transition hover:text-gold focus:outline-none focus-visible:ring-2 focus-visible:ring-gold">
                            <svg x-show="! muted" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M11 5 6 9H3v6h3l5 4V5Z" />
                                <path d="M15.5 8.5a5 5 0 0 1 0 7M18.5 5.5a9 9 0 0 1 0 13" />
                            </svg>
                            <svg x-show="muted" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" aria-hidden="true">
                                <path d="M11 5 6 9H3v6h3l5 4V5Z" />
                                <path d="m16 9 5 6M21 9l-5 6" />
                            </svg>
                        </button>

                        {{-- Phones use their hardware volume buttons, so the slider is for larger screens --}}
                        <input type="range" min="0" max="1" step="0.05"
                            :value="muted ? 0 : volume" x-on:input="setVolume(Number($event.target.value))"
                            :style="`--fill: ${(muted ? 0 : volume) * 100}%`" aria-label="Volume"
                            class="player-range hidden w-20 sm:block">
                    </div>

                    <button type="button" x-show="canFullscreen" x-on:click="toggleFullscreen()"
                        :aria-label="fullscreen ? 'Exit fullscreen' : 'Fullscreen'"
                        class="p-1.5 transition hover:text-gold focus:outline-none focus-visible:ring-2 focus-visible:ring-gold">
                        <svg x-show="! fullscreen" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M4 9V4h5M20 9V4h-5M4 15v5h5M20 15v5h-5" />
                        </svg>
                        <svg x-show="fullscreen" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M9 4v5H4M15 4v5h5M9 20v-5H4M15 20v-5h5" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Several videos: pick one. A single video just shows its caption, if it has one. --}}
    @if (count($videos) > 1)
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <template x-for="(video, i) in videos" :key="i">
                <button type="button" x-on:click="select(i)" :aria-current="i === index ? 'true' : 'false'"
                    :class="i === index ? 'border-gold bg-gold text-navy' :
                        'border-ivory/30 text-ivory hover:border-gold hover:text-gold'"
                    class="border px-5 py-2 text-xs font-semibold uppercase tracking-[0.2em] transition"
                    x-text="video.caption || 'Film ' + (i + 1)"></button>
            </template>
        </div>
    @elseif (filled($videos[0]['caption'] ?? null))
        <p class="mt-5 text-center text-sm text-ivory/70">{{ $videos[0]['caption'] }}</p>
    @endif
</div>
