function register() {
    window.Alpine.data('dayCarousel', () => {
        let observer;
        return {
            current: 0,
            total: 0,
            init() {
                this.total = this.$refs.track.children.length;
                observer = new ResizeObserver(() => this.show(this.current, 'instant'));
                observer.observe(this.$refs.track);
            },
            position(index) {
                const slides = this.$refs.track.children;
                return slides[index].offsetLeft - slides[0].offsetLeft;
            },
            show(index, behavior) {
                this.current = Math.max(0, Math.min(this.total - 1, index));
                this.$refs.track.scrollTo({
                    left: this.position(this.current),
                    behavior: behavior ?? (window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth'),
                });
            },
            go(direction) {
                this.show(this.current + direction);
            },
            sync() {
                const left = this.$refs.track.scrollLeft;
                let closest = 0;
                for (let index = 1; index < this.total; index++) {
                    if (Math.abs(this.position(index) - left) < Math.abs(this.position(closest) - left)) closest = index;
                }
                this.current = closest;
            },
            destroy() {
                observer.disconnect();
            },
        };
    });
}

if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });
