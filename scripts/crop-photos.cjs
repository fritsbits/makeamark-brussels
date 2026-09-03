// Crop the Instagram-story screenshots down to the photographs themselves,
// dropping the app chrome. The strip photos are normalised to 4:5; the
// collage keeps its own taller proportion because it carries the hero.
const sharp = require('sharp');
const fs = require('node:fs');

const SRC = '/Users/frederikvincx/Documents/makeamark/site/src/assets/stories';
const OUT = '/Users/frederikvincx/Documents/makeamark/site/src/assets/photos';
fs.mkdirSync(OUT, { recursive: true });

// Crops in source pixels (the sources are 640x1138 story screenshots).
// Every candidate the design considered is kept here as a record; only the
// entries marked `ship` are written into src/assets/photos, so the repo holds
// exactly the images the site uses. Flip a flag and re-run to swap one in.
const crops = {
  'story-3.jpg': { out: 'collage.jpg', left: 15, top: 75, width: 610, height: 1000, w: 610, h: 1000, warm: true },
  'story-9.jpg': { out: 'shoot.jpg', left: 26, top: 400, width: 560, height: 220, w: 1440, h: 566, duotone: true, ship: true },
  'story-5.jpg': { out: 'mural.jpg', left: 90, top: 396, width: 460, height: 212, w: 920, h: 424, duotone: true },
  'story-8.jpg': { out: 'stage.jpg', left: 60, top: 178, width: 424, height: 530 },
  'story-4.jpg': { out: 'sketches.jpg', left: 83, top: 470, width: 490, height: 612 },
  'story-1.jpg': { out: 'floor.jpg', left: 100, top: 500, width: 290, height: 362 },
  'story-6.jpg': { out: 'team.jpg', left: 180, top: 408, width: 272, height: 340 },
};

(async () => {
  for (const [src, c] of Object.entries(crops)) {
    if (!c.ship) continue;
    const meta = await sharp(`${SRC}/${src}`).metadata();
    const width = Math.min(c.width, meta.width - c.left);
    const height = Math.min(c.height, meta.height - c.top);
    let pipe = sharp(`${SRC}/${src}`)
      .extract({ left: c.left, top: c.top, width, height })
      .resize(c.w || 1000, c.h || 1250, { fit: 'cover' });
    // The page is a two-ink print job. Full-colour snapshots break that
    // illusion, so every photograph is duotoned: shadows take the red,
    // highlights take the cream, and the low-resolution sources stop shouting.
    if (c.warm) pipe = pipe.linear([1.07, 1.0, 0.925], [0, 0, 0]);
    if (c.duotone) {
      // One photo treatment on the whole page, and it uses the page's own
      // inks: shadows take a deep brick, highlights land on the paper cream.
      const ramp = { a: [0.482, 0.784, 0.686], b: [122, 26, 27] };
      // greyscale drops to one band, so re-expand to three before mapping
      // deepen the shadows first: these sources are flat phone exposures and
      // a duotone applied to them lands pale
      const flat = await pipe.greyscale().normalise().linear(1.52, -56).png().toBuffer();
      const toned = await sharp(flat).toColourspace('srgb').linear(ramp.a, ramp.b).png().toBuffer();
      // a fine grain, the way a one-colour print carries one: it gives the
      // low-resolution sources a surface instead of a soft blur
      const { width: gw, height: gh } = await sharp(toned).metadata();
      const grain = await sharp({
        create: { width: gw, height: gh, channels: 3, noise: { type: 'gaussian', mean: 128, sigma: 14 } },
      })
        .png()
        .toBuffer();
      pipe = sharp(toned).composite([{ input: grain, blend: 'overlay' }]);
    }
    await pipe.jpeg({ quality: 88, mozjpeg: true }).toFile(`${OUT}/${c.out}`);
    console.log(src, '->', c.out);
  }
})();
