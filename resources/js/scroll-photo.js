function register() {
    window.Alpine.data('scrollPhoto', () => {
        let frame = null;
        let motion;
        let update;
        let render;

        return {
            init() {
                motion = window.matchMedia('(prefers-reduced-motion: reduce)');
                render = () => {
                    frame = null;
                    // Measure the stable stage, never the photograph being scaled.
                    const top = this.$el.getBoundingClientRect().top;
                    const viewport = window.innerHeight;
                    const progress = Math.max(0, Math.min(1, (viewport * .95 - top) / (viewport * .67)));
                    const minimum = window.innerWidth <= 860 ? .9 : .75;
                    const scale = motion.matches ? 1 : minimum + (1 - minimum) * progress;
                    this.$el.querySelector('.distilled-evidence__photo').style.setProperty('--photo-scale', scale);
                };
                update = () => {
                    if (frame === null) frame = window.requestAnimationFrame(render);
                };
                window.addEventListener('scroll', update, { passive: true });
                window.addEventListener('resize', update);
                motion.addEventListener('change', update);
                this.$nextTick(update);
            },
            destroy() {
                if (frame !== null) window.cancelAnimationFrame(frame);
                window.removeEventListener('scroll', update);
                window.removeEventListener('resize', update);
                motion.removeEventListener('change', update);
            },
        };
    });
}

if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });
