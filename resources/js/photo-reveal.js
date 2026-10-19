function register() {
    window.Alpine.data('photoReveal', () => {
        let animation = null;
        let progress = 0;
        let motion;
        let onMotionChange;

        return {
            ready: false, hovered: false, pinned: false, reducedMotion: false,
            async init() {
                motion = window.matchMedia('(prefers-reduced-motion: reduce)');
                onMotionChange = () => {
                    this.reducedMotion = motion.matches;
                    this.reveal(this.hovered || this.pinned);
                };
                this.reducedMotion = motion.matches;
                motion.addEventListener('change', onMotionChange);
                try {
                    await this.$refs.original.decode();
                    this.ready = true;
                } catch {
                    // Keep the base image if the original cannot be loaded.
                }
            },
            enter(event) {
                if (!this.ready || event.pointerType === 'touch') return;
                if (!this.pinned && progress === 0) {
                    const photo = this.$refs.photo;
                    const bounds = photo.getBoundingClientRect();
                    const x = Math.max(0, Math.min(photo.clientWidth, (event.clientX - bounds.left) * photo.clientWidth / bounds.width));
                    const y = Math.max(0, Math.min(photo.clientHeight, (event.clientY - bounds.top) * photo.clientHeight / bounds.height));
                    this.$refs.original.style.setProperty('--reveal-x', `${x}px`);
                    this.$refs.original.style.setProperty('--reveal-y', `${y}px`);
                }
                this.hovered = true;
                this.reveal(true);
            },
            leave() {
                this.hovered = false;
                this.reveal(this.pinned);
            },
            toggle() {
                if (!this.ready) return;
                this.pinned = !this.pinned;
                this.hovered = false;
                this.reveal(this.pinned);
            },
            restore() {
                this.hovered = false;
                this.pinned = false;
                this.reveal(false);
            },
            reveal(show) {
                cancelAnimationFrame(animation);
                const target = show ? 1 : 0;
                const start = progress;
                const paint = (value) => {
                    progress = value;
                    const photo = this.$refs.photo;
                    const radius = Math.hypot(photo.clientWidth, photo.clientHeight) + 40;
                    this.$refs.original.style.setProperty('--reveal-progress', String(value));
                    this.$refs.original.style.setProperty('--reveal-radius', `${radius * value}px`);
                    this.$refs.original.style.opacity = value > 0 ? '1' : '0';
                };
                if (this.reducedMotion || start === target) {
                    paint(target);
                    return;
                }
                const began = performance.now();
                const tick = (now) => {
                    const elapsed = Math.min(1, (now - began) / 700);
                    const eased = 1 - (1 - elapsed) ** 3;
                    paint(elapsed === 1 ? target : start + (target - start) * eased);
                    if (elapsed < 1) animation = requestAnimationFrame(tick);
                };
                animation = requestAnimationFrame(tick);
            },
            destroy() {
                cancelAnimationFrame(animation);
                motion?.removeEventListener('change', onMotionChange);
            },
        };
    });
}

if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });
