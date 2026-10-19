import test from 'node:test';
import assert from 'node:assert/strict';
import { renderPixels, normaliseSettings, defaults } from '../../resources/js/riso-engine.js';

test('unprinted white areas retain the chosen paper colour', () => {
    const pixels = new Uint8ClampedArray([255, 255, 255, 255]);
    const result = renderPixels(pixels, 1, 1, { ...defaults, paper: '#f8ead3', texture: 0, contrast: 0, exposure: 0 });
    assert.deepEqual([...result], [248, 234, 211, 255]);
});

test('dark photo areas print ink and repeated renders keep the same grain', () => {
    const pixels = new Uint8ClampedArray(16 * 16 * 4).fill(255);
    for (let i = 0; i < pixels.length; i += 4) pixels[i] = pixels[i + 1] = pixels[i + 2] = 30;
    const first = renderPixels(pixels, 16, 16, defaults);
    assert.deepEqual(first, renderPixels(pixels, 16, 16, defaults));
    assert.ok(first.some((v, i) => i % 4 !== 3 && v < 100), 'shadows should contain dark ink');
});

test('turning off both ink plates leaves only paper', () => {
    const result = renderPixels(new Uint8ClampedArray([0, 0, 0, 255]), 1, 1, { ...defaults, redOpacity: 0, blueOpacity: 0, texture: 0 });
    assert.deepEqual([...result], [248, 234, 211, 255]);
});

test('imported settings are bounded and reject unsupported colours and keys', () => {
    const settings = normaliseSettings({ contrast: 999, grain: -4, offset: 'bad', red: 'url(evil)', paper: '#ffffff', unknown: true });
    assert.equal(settings.contrast, 80);
    assert.equal(settings.grain, 0);
    assert.equal(settings.offset, defaults.offset);
    assert.equal(settings.red, defaults.red);
    assert.equal(settings.paper, '#ffffff');
    assert.equal('unknown' in settings, false);
});
