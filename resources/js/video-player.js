export default (videos) => ({
    videos,
    index: 0,
    playing: false,
    buffering: false,
    current: 0,
    duration: 0,
    volume: 1,
    muted: false,
    fullscreen: false,
    canFullscreen: false,
    idle: false,
    overBar: false,
    idleTimer: null,

    init() {
        this.canFullscreen = Boolean(
            document.fullscreenEnabled || this.video.webkitEnterFullscreen,
        );
    },

    get video() {
        return this.$refs.video;
    },

    get active() {
        return this.videos[this.index];
    },

    // Seek bar position on a 0-1000 scale (finer than whole seconds, so short clips move smoothly)
    get position() {
        return this.duration ? (this.current / this.duration) * 1000 : 0;
    },

    get percent() {
        return this.duration ? (this.current / this.duration) * 100 : 0;
    },

    get showControls() {
        return !this.playing || !this.idle;
    },

    format(seconds) {
        if (!Number.isFinite(seconds)) return "0:00";

        const total = Math.floor(seconds);
        const h = Math.floor(total / 3600);
        const m = Math.floor((total % 3600) / 60);
        const s = String(total % 60).padStart(2, "0");

        return h > 0 ? `${h}:${String(m).padStart(2, "0")}:${s}` : `${m}:${s}`;
    },

    play() {
        const attempt = this.video.play();

        // play() returns a promise that rejects if it is interrupted (for example by switching videos)
        if (attempt) attempt.catch(() => {});
    },

    toggle() {
        this.video.paused || this.video.ended
            ? this.play()
            : this.video.pause();
    },

    onMetadata() {
        this.duration = Number.isFinite(this.video.duration)
            ? this.video.duration
            : 0;
    },

    onTime() {
        this.current = this.video.currentTime;
    },

    onPlay() {
        this.playing = true;
        this.wake();
    },

    onPause() {
        this.playing = false;
        this.idle = false;
    },

    seekTo(value) {
        if (!this.duration) return;

        this.video.currentTime = (Number(value) / 1000) * this.duration;
        this.current = this.video.currentTime;
    },

    seekBy(seconds) {
        if (!this.duration) return;

        this.video.currentTime = Math.min(
            Math.max(0, this.video.currentTime + seconds),
            this.duration,
        );
        this.current = this.video.currentTime;
    },

    syncVolume() {
        this.volume = this.video.volume;
        this.muted = this.video.muted || this.video.volume === 0;
    },

    setVolume(value) {
        this.video.muted = value === 0;
        this.video.volume = value;
    },

    toggleMute() {
        this.video.muted = !this.video.muted;

        if (!this.video.muted && this.video.volume === 0) {
            this.video.volume = 0.6;
        }
    },

    async toggleFullscreen() {
        try {
            if (document.fullscreenElement) {
                await document.exitFullscreen();
            } else if (this.$refs.stage.requestFullscreen) {
                await this.$refs.stage.requestFullscreen();
            } else if (this.video.webkitEnterFullscreen) {
                this.video.webkitEnterFullscreen(); // iPhone Safari
            }
        } catch (e) {
            // the browser refused (for example, no user gesture): nothing to do
        }
    },

    select(i) {
        if (i === this.index) return;

        this.index = i;
        this.playing = false;
        this.current = 0;
        this.duration = 0;

        // wait for Alpine to swap the src, then start the chosen video
        this.$nextTick(() => this.play());
    },

    // Show the controls, then hide them again after a short pause (only while playing)
    wake() {
        this.idle = false;
        clearTimeout(this.idleTimer);

        if (this.playing) {
            this.idleTimer = setTimeout(() => {
                if (!this.overBar) this.idle = true;
            }, 2500);
        }
    },
});
