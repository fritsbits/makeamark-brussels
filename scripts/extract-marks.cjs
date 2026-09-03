// Pull the hand-drawn marks out of the flat logo PNG and re-issue them as
// transparent PNGs in the brand colours, so they can sit on cream or on red.
// Run: NODE_PATH=./node_modules node scripts/extract-marks.cjs
const sharp = require('sharp');
const path = require('node:path');
const fs = require('node:fs');

const ROOT = path.resolve(__dirname, '..');
const SRC = path.join(ROOT, 'src/assets/logo-brussels.png');
const OUT = path.join(ROOT, 'public/marks');
fs.mkdirSync(OUT, { recursive: true });

// crops in source pixels (source is 899x871)
const regions = {
  brussels: { left: 102, top: 340, width: 742, height: 192 },
};

const inks = { red: '#C33F40', cream: '#F5E2CA', ink: '#1E1A16' };

/** Grow the ink with a disc-shaped structuring element. A square (separable)
 *  dilation leaves stepped corners and squared-off stroke ends; a disc grows
 *  the stroke the way a broader nib would, with round ends, and needs no blur
 *  afterwards, so the edge stays as hard as the type beside it. */
function dilateDisc(src, width, height, r) {
  const offsets = [];
  for (let dy = -r; dy <= r; dy++) {
    for (let dx = -r; dx <= r; dx++) {
      if (dx * dx + dy * dy <= r * r) offsets.push([dx, dy]);
    }
  }
  const out = Buffer.alloc(width * height);
  for (let y = 0; y < height; y++) {
    for (let x = 0; x < width; x++) {
      let max = 0;
      for (let i = 0; i < offsets.length; i++) {
        const xi = x + offsets[i][0];
        const yi = y + offsets[i][1];
        if (xi < 0 || xi >= width || yi < 0 || yi >= height) continue;
        const v = src[yi * width + xi];
        if (v > max) max = v;
      }
      out[y * width + x] = max;
    }
  }
  return out;
}


async function mark(name, region, grow) {
  // the source is flat white paper with black ink, so an inverted greyscale
  // of the crop is exactly the ink coverage we want as an alpha channel
  let { data, info } = await sharp(SRC)
    .extract(region)
    .greyscale()
    .toColourspace('b-w')
    .normalise()
    .negate()
    .raw()
    .toBuffer({ resolveWithObject: true });
  let { width, height } = info;
  // the paper carries faint pencil ghosts; drop them before growing the ink
  for (let i = 0; i < data.length; i++) if (data[i] < 60) data[i] = 0;

  if (grow) {
    // resample up to the size the mark is actually drawn at, re-harden the
    // edge the interpolation softened, then thicken it to marker weight
    const scale = 2.6;
    width = Math.round(width * scale);
    height = Math.round(height * scale);
    const up = await sharp(data, { raw: { width: info.width, height: info.height, channels: 1 } })
      .resize(width, height, { kernel: 'lanczos3' })
      .threshold(128)
      .toColourspace('b-w')
      .raw()
      .toBuffer({ resolveWithObject: true });
    // threshold can hand back an interleaved image; flatten to one plane so the
    // dilation reads the right stride
    let plane = up.data;
    if (up.info.channels > 1) {
      const n = up.info.channels;
      const single = Buffer.alloc(width * height);
      for (let i = 0; i < width * height; i++) single[i] = up.data[i * n];
      plane = single;
    }
    // `grow` is a radius in the upscaled pixels the mark is drawn at
    data = dilateDisc(plane, width, height, grow);

    // a marker does not lay down one uniform weight: modulating the coverage
    // with a soft noise field and re-thresholding roughens the edge and thins
    // the stroke unevenly, the way a drying nib does
    const noise = await sharp({
      create: { width, height, channels: 1, noise: { type: 'gaussian', mean: 128, sigma: 60 } },
    })
      .blur(2.4)
      .toColourspace('b-w')
      .raw()
      .toBuffer();
    for (let i = 0; i < data.length; i++) {
      if (data[i] === 0) continue;
      data[i] = noise[i] > 96 ? 255 : 0;
    }
    data = dilateDisc(data, width, height, 1);
  }

  const alpha = await sharp(data, { raw: { width, height, channels: 1 } }).png().toBuffer();
  for (const [ink, hex] of Object.entries(inks)) {
    await sharp({ create: { width, height, channels: 3, background: hex } })
      .joinChannel(alpha)
      .png({ compressionLevel: 9 })
      .toFile(path.join(OUT, `${name}-${ink}.png`));
  }
  console.log(name, `${width}x${height}`);
}

(async () => {
  for (const [name, region] of Object.entries(regions)) await mark(name, region, 7);
})();
