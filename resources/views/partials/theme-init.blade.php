{{-- Runs before the first paint, so the page never flashes the wrong theme --}}
<script>
    (function() {
        try {
            var saved = localStorage.getItem('amiana.theme');
            var dark = saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle('dark', dark);
        } catch (e) {}
    })();
</script>
