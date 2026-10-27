export default (limits) => ({
    limits,
    busy: false,
    count: 0,
    problems: [],

    // Runs when files are chosen: report anything over the limit before uploading
    check(files) {
        this.count = files.length;
        this.problems = [];

        for (const file of files) {
            const isVideo = file.type.startsWith("video/");
            const limit = isVideo ? this.limits.video : this.limits.image;

            if (file.size > limit) {
                const size = (file.size / 1048576).toFixed(1);
                const kind = isVideo ? "Videos" : "Photos";
                const max = isVideo
                    ? this.limits.videoLabel
                    : this.limits.imageLabel;

                this.problems.push(
                    `${file.name} is ${size} MB. ${kind} can be ${max} at most.`,
                );
            }
        }
    },

    submit(event) {
        if (this.problems.length > 0) {
            event.preventDefault();

            return;
        }

        this.busy = true;
    },
});
