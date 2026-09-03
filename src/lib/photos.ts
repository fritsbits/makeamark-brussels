import type { ImageMetadata } from 'astro';

const files = import.meta.glob<{ default: ImageMetadata }>('../assets/photos/*.{jpg,jpeg,png}', { eager: true });

/** Photographs are cropped, duotoned and grained by scripts/crop-photos.cjs
 *  from the Instagram-story sources in src/assets/stories. */
export function photo(file: string): ImageMetadata {
  const mod = files[`../assets/photos/${file}`];
  if (!mod) throw new Error(`Photo not found in src/assets/photos: ${file}`);
  return mod.default;
}
