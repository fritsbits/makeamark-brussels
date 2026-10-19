export const defaults = Object.freeze({
    red: '#ff3045', blue: '#17469d', paper: '#f8ead3',
    contrast: 18, exposure: 3, balance: 48, grain: 80, grainSize: 1.2,
    texture: 35, offset: 2, margin: 4, redOpacity: 100, blueOpacity: 100,
});

const limits = { contrast: [-40, 80], exposure: [-40, 40], balance: [0, 100], grain: [0, 100], grainSize: [0.6, 3], texture: [0, 100], offset: [0, 10], margin: [0, 12], redOpacity: [0, 100], blueOpacity: [0, 100] };
const clamp = (value, min = 0, max = 1) => Math.max(min, Math.min(max, value));
const rgb = (hex) => [1, 3, 5].map((start) => parseInt(hex.slice(start, start + 2), 16));

export function normaliseSettings(input = {}) {
    const settings = { ...defaults };
    if (!input || typeof input !== 'object' || Array.isArray(input)) return settings;
    for (const [key, [min, max]] of Object.entries(limits)) {
        if (typeof input[key] === 'number' && Number.isFinite(input[key])) settings[key] = clamp(input[key], min, max);
    }
    for (const key of ['red', 'blue', 'paper']) {
        if (typeof input[key] === 'string' && /^#[\da-f]{6}$/i.test(input[key])) settings[key] = input[key];
    }
    return settings;
}

// Integer hashing keeps the printed texture stable while the controls change.
function noise(x, y, seed) {
    let n = Math.imul(x + seed, 374761393) + Math.imul(y + seed, 668265263);
    n = Math.imul(n ^ (n >>> 13), 1274126177);
    return ((n ^ (n >>> 16)) >>> 0) / 4294967295;
}

export function renderPixels(source, width, height, input) {
    const s = normaliseSettings(input);
    const paper = rgb(s.paper), red = rgb(s.red), blue = rgb(s.blue);
    const output = new Uint8ClampedArray(source.length);
    const darkness = new Float32Array(width * height);
    const contrast = (100 + s.contrast) / (100 - s.contrast);
    for (let i = 0; i < darkness.length; i++) {
        const p = i * 4;
        const alpha = source[p + 3] / 255;
        const luminance = ((source[p] * 0.299 + source[p + 1] * 0.587 + source[p + 2] * 0.114) / 255) * alpha + 1 - alpha;
        darkness[i] = 1 - clamp((luminance - 0.5) * contrast + 0.5 + s.exposure / 100);
    }
    const scale = Math.max(width, height) / 1200;
    const shiftX = Math.round(s.offset * scale), shiftY = Math.round(s.offset * scale * 0.45);
    const grain = s.grain / 100;
    for (let y = 0; y < height; y++) {
        for (let x = 0; x < width; x++) {
            const i = y * width + x, p = i * 4;
            const gx = Math.floor(x / (s.grainSize * scale)), gy = Math.floor(y / (s.grainSize * scale));
            const bx = x - shiftX, by = y - shiftY;
            const blueDarkness = bx >= 0 && by >= 0 ? darkness[by * width + bx] : 0;
            const blueCoverage = clamp((blueDarkness - (0.57 - s.balance * 0.0045)) * 2.3, 0, 0.96);
            // Under-colour removal keeps overlapping shadow ink blue rather than black.
            const redCoverage = clamp(darkness[i] * 1.85) * (1 - blueCoverage * 0.65 * s.blueOpacity / 100);
            const r = ((1 - grain) * redCoverage + grain * (noise(gx, gy, 17) < redCoverage ? 1 : 0)) * s.redOpacity / 100;
            const b = ((1 - grain) * blueCoverage + grain * (noise(gx, gy, 83) < blueCoverage ? 1 : 0)) * s.blueOpacity / 100;
            const texture = (noise(gx, gy, 127) - 0.5) * s.texture * 0.24;
            for (let c = 0; c < 3; c++) output[p + c] = paper[c] * (1 - r * (1 - red[c] / 255)) * (1 - b * (1 - blue[c] / 255)) + texture;
            output[p + 3] = 255;
        }
    }
    return output;
}
