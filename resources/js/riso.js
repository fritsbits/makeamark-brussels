import { defaults, normaliseSettings, renderPixels } from './riso-engine';

function register() {
    window.Alpine.data('risoStudio', () => ({
        settings: { ...defaults }, image: null, ready: false, busy: false, original: false,
        filename: 'Make a Mark group photo', dimensions: '', error: '', notice: '', preset: 'reference',
        exportWidth: '2400', timer: null, imageRequest: 0,
        async init() {
            this.$watch('settings', () => { this.preset = ''; this.schedule(); });
            await this.loadImage('/tools/riso/sample', 'Make a Mark group photo');
        },
        async loadImage(url, name) {
            const request = ++this.imageRequest;
            this.busy = true;
            this.error = '';
            try {
                const img = new Image();
                img.src = url;
                await img.decode();
                if (request !== this.imageRequest) return;
                if (img.naturalWidth * img.naturalHeight > 40000000) throw new Error('This photo is too large. Choose one under 40 megapixels.');
                this.image = img;
                this.filename = name;
                this.dimensions = `${img.naturalWidth} × ${img.naturalHeight} px`;
                this.ready = true;
                this.original = false;
                this.paint();
            } catch (error) {
                if (request === this.imageRequest) this.error = error.message.includes('megapixels') ? error.message : 'This photo could not be opened. Try a JPG, PNG or WebP image.';
            } finally {
                if (request === this.imageRequest) this.busy = false;
            }
        },
        async upload(event) {
            const file = event.target.files?.[0];
            if (!file) return;
            event.target.value = '';
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 20 * 1024 * 1024) {
                this.error = 'Choose a JPG, PNG or WebP photo under 20 MB.';
                return;
            }
            const url = URL.createObjectURL(file);
            try { await this.loadImage(url, file.name); } finally { URL.revokeObjectURL(url); }
        },
        schedule() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => this.paint(), 80);
        },
        createCanvas(longEdge, original = false) {
            const s = normaliseSettings(this.settings);
            const ratio = this.image.naturalWidth / this.image.naturalHeight;
            const width = Math.round(ratio >= 1 ? longEdge : longEdge * ratio);
            const height = Math.round(ratio >= 1 ? longEdge / ratio : longEdge);
            const photo = document.createElement('canvas');
            photo.width = width; photo.height = height;
            const ctx = photo.getContext('2d', { willReadFrequently: true });
            ctx.drawImage(this.image, 0, 0, width, height);
            if (!original) {
                const data = ctx.getImageData(0, 0, width, height);
                data.data.set(renderPixels(data.data, width, height, s));
                ctx.putImageData(data, 0, 0);
            }
            const margin = Math.round(longEdge * s.margin / 100);
            const canvas = document.createElement('canvas');
            canvas.width = width + margin * 2; canvas.height = height + margin * 2;
            const out = canvas.getContext('2d');
            out.fillStyle = s.paper;
            out.fillRect(0, 0, canvas.width, canvas.height);
            // Continue the same paper texture into the unprinted border.
            if (!original && margin > 0 && s.texture > 0) {
                const paper = out.getImageData(0, 0, canvas.width, canvas.height);
                for (let i = 0; i < paper.data.length; i += 4) {
                    let hash = Math.imul(i / 4 + 127, 374761393);
                    hash = Math.imul(hash ^ (hash >>> 13), 1274126177);
                    const tone = (((hash ^ (hash >>> 16)) >>> 0) / 4294967295 - 0.5) * s.texture * 0.24;
                    paper.data[i] += tone; paper.data[i + 1] += tone; paper.data[i + 2] += tone;
                }
                out.putImageData(paper, 0, 0);
            }
            out.drawImage(photo, margin, margin);
            return canvas;
        },
        paint() {
            if (!this.image) return;
            try {
                const result = this.createCanvas(1200, this.original);
                const canvas = this.$refs.preview;
                canvas.width = result.width; canvas.height = result.height;
                canvas.getContext('2d').drawImage(result, 0, 0);
            } catch { this.error = 'The preview could not be rendered. Try a smaller photo.'; }
        },
        compare(value) { this.original = value; this.paint(); },
        applyPreset(name) {
            const presets = {
                reference: { ...defaults },
                soft: { ...defaults, contrast: 8, grain: 50, blueOpacity: 75, texture: 20 },
                red: { ...defaults, blueOpacity: 0, contrast: 12, grain: 90 },
            };
            this.settings = { ...presets[name] };
            this.$nextTick(() => { this.preset = name; });
        },
        download(blob, name) {
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url; link.download = name; link.click();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        },
        async exportPng() {
            if (!this.ready || this.busy) return;
            this.busy = true; this.error = ''; this.notice = '';
            await new Promise((resolve) => setTimeout(resolve, 40));
            try {
                const canvas = this.createCanvas(Number(this.exportWidth));
                const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png'));
                if (!blob) throw new Error('export');
                this.download(blob, `${this.filename.replace(/\.[^.]+$/, '').replace(/[^\p{L}\p{N}_ -]/gu, '').slice(0, 70) || 'photo'}-riso.png`);
                this.notice = `PNG downloaded · ${canvas.width} × ${canvas.height} px`;
            } catch { this.error = 'The PNG could not be created. Try the smaller export size.'; }
            finally { this.busy = false; }
        },
        saveSettings() {
            this.download(new Blob([JSON.stringify({ version: 1, settings: normaliseSettings(this.settings) }, null, 2)], { type: 'application/json' }), 'risoprint-treatment.json');
            this.notice = 'Treatment settings downloaded.';
        },
        async importSettings(event) {
            const file = event.target.files?.[0];
            if (!file) return;
            event.target.value = '';
            try {
                if (file.size > 10000) throw new Error('size');
                const data = JSON.parse(await file.text());
                if (data.version !== 1 || !data.settings || typeof data.settings !== 'object' || Array.isArray(data.settings)) throw new Error('format');
                this.settings = normaliseSettings(data.settings);
                this.error = ''; this.notice = 'Treatment settings loaded.';
            } catch { this.error = 'Choose a treatment JSON file saved by this tool.'; }
        },
    }));
}

if (window.Alpine) register();
else document.addEventListener('alpine:init', register, { once: true });
