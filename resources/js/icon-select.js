export default (options) => ({
    options,
    value: "star",
    open: false,
    query: "",
    activeName: null,

    get filtered() {
        const q = this.query.trim().toLowerCase();

        return q
            ? this.options.filter((o) => o.search.includes(q))
            : this.options;
    },

    get selectedLabel() {
        return this.options.find((o) => o.value === this.value)?.label ?? "";
    },

    shows(name) {
        return this.filtered.some((o) => o.value === name);
    },

    toggle() {
        this.open ? this.close() : this.openList();
    },

    openList() {
        this.query = "";
        this.activeName = this.value;
        this.open = true;

        this.$nextTick(() => {
            this.$refs.search.focus();
            this.scrollToActive();
        });
    },

    close(refocus = false) {
        this.open = false;

        if (refocus) this.$refs.trigger.focus();
    },

    choose(name) {
        if (!name) return;

        this.value = name;
        this.close(true);
    },

    // After typing, the first match becomes the highlighted one
    onSearch() {
        this.activeName = this.filtered[0]?.value ?? null;
        this.scrollToActive();
    },

    move(step) {
        const list = this.filtered;

        if (list.length === 0) return;

        const current = list.findIndex((o) => o.value === this.activeName);
        const next =
            current === -1
                ? step > 0
                    ? 0
                    : list.length - 1
                : (current + step + list.length) % list.length;

        this.activeName = list[next].value;
        this.scrollToActive();
    },

    scrollToActive() {
        this.$nextTick(() => {
            this.$refs.list
                ?.querySelector('[data-active="true"]')
                ?.scrollIntoView({ block: "nearest" });
        });
    },
});
