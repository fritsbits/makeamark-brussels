import test from 'node:test';
import assert from 'node:assert/strict';
import { mkdtemp, mkdir, writeFile, readFile, readdir, rm } from 'node:fs/promises';
import { tmpdir } from 'node:os';
import { join } from 'node:path';
import sharp from 'sharp';
import { buildPhotoTreatments } from '../../scripts/build-photo-treatments.mjs';

test('build treats only opted-in photos and keeps originals and responsive dimensions', async () => {
    const root = await mkdtemp(join(tmpdir(), 'riso-build-'));
    try {
        for (const path of ['resources/content/editions', 'resources/content/photo-treatments', 'public/images']) await mkdir(join(root, path), { recursive: true });
        await writeFile(join(root, 'resources/content/editions/2027.json'), JSON.stringify({ photo: 'group.jpg', photoTreatment: 'riso' }));
        await writeFile(join(root, 'resources/content/stories.json'), JSON.stringify([{ file: 'story.jpg', treatment: 'monochrome' }]));
        await writeFile(join(root, 'resources/content/photo-treatments/riso.json'), await readFile(new URL('../../resources/content/photo-treatments/riso.json', import.meta.url)));
        const source = await sharp({ create: { width: 80, height: 60, channels: 3, background: '#717171' } }).webp().toBuffer();
        await writeFile(join(root, 'public/images/group.80.webp'), source);
        await sharp(source).resize(40).toFile(join(root, 'public/images/group.40.webp'));
        await writeFile(join(root, 'public/images/story.80.webp'), source);
        await buildPhotoTreatments(root);
        const output = join(root, 'public/images/treatments/riso');
        assert.deepEqual((await readdir(output)).sort(), ['group.40.webp', 'group.80.webp']);
        assert.equal((await sharp(join(output, 'group.80.webp')).metadata()).width, 80);
        assert.equal((await sharp(join(output, 'group.40.webp')).metadata()).height, 30);
        assert.notDeepEqual(await readFile(join(output, 'group.80.webp')), source);
        assert.deepEqual(await readFile(join(root, 'public/images/group.80.webp')), source);
    } finally { await rm(root, { recursive: true, force: true }); }
});
