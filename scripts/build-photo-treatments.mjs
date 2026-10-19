import { readFile, readdir, mkdir } from 'node:fs/promises';
import { dirname, join, resolve, parse } from 'node:path';
import { fileURLToPath, pathToFileURL } from 'node:url';
import sharp from 'sharp';
import { normaliseSettings, renderPixels } from '../resources/js/riso-engine.js';

const projectRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const readJson = async (path) => JSON.parse(await readFile(path, 'utf8'));

export async function buildPhotoTreatments(root = projectRoot) {
    const content = join(root, 'resources/content');
    const editions = await Promise.all((await readdir(join(content, 'editions')))
        .filter(file => file.endsWith('.json')).map(file => readJson(join(content, 'editions', file))));
    const stories = await readJson(join(content, 'stories.json'));
    const selected = [
        ...editions.map(edition => ({ file: edition.photo, treatment: edition.photoTreatment })),
        ...stories.map(story => ({ file: story.file, treatment: story.treatment })),
    ].filter(photo => photo.treatment && photo.treatment !== 'monochrome');
    const imageRoot = join(root, 'public/images');
    const imageFiles = await readdir(imageRoot);
    const completed = new Set();

    for (const photo of selected) {
        if (photo.treatment !== 'riso') throw new Error(`Unknown photo treatment: ${photo.treatment}`);
        if (typeof photo.file !== 'string' || !/^[a-z\d][a-z\d_-]*\.(jpe?g|png|webp)$/i.test(photo.file)) throw new Error('Photo filenames must be simple image basenames.');
        const name = parse(photo.file).name;
        if (completed.has(name)) continue;
        const preset = await readJson(join(content, 'photo-treatments/riso.json'));
        if (preset.version !== 1 || !preset.settings || Array.isArray(preset.settings) || typeof preset.settings !== 'object') throw new Error('Invalid riso treatment. Save a version 1 treatment from the configurator.');
        const settings = normaliseSettings(preset.settings);
        const variants = imageFiles.filter(file => file.startsWith(`${name}.`))
            .map(file => ({ file, width: Number(file.slice(name.length + 1).match(/^(\d+)\.webp$/)?.[1]) }))
            .filter(variant => variant.width > 0).sort((a, b) => a.width - b.width);
        if (!variants.length) throw new Error(`No responsive WebP sources found for ${photo.file}`);

        // Match the studio's 1200px preview before making responsive copies.
        const { data, info } = await sharp(join(imageRoot, variants.at(-1).file), { limitInputPixels: 40000000 })
            .rotate().resize({ width: 1200, height: 1200, fit: 'inside' }).toColourspace('srgb').ensureAlpha().raw().toBuffer({ resolveWithObject: true });
        const pixels = renderPixels(data, info.width, info.height, settings);
        const output = join(imageRoot, 'treatments/riso');
        await mkdir(output, { recursive: true });
        for (const variant of variants) {
            await sharp(Buffer.from(pixels), { raw: { width: info.width, height: info.height, channels: 4 } })
                .resize({ width: variant.width }).webp({ quality: 92 }).toFile(join(output, variant.file));
        }
        completed.add(name);
    }
    return completed.size;
}

if (process.argv[1] && import.meta.url === pathToFileURL(resolve(process.argv[1])).href) {
    const count = await buildPhotoTreatments();
    console.log(`Photo treatments: ${count} selected photo${count === 1 ? '' : 's'} built.`);
}
