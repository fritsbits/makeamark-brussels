import type { ImageMetadata } from 'astro';

const images = import.meta.glob<{ default: ImageMetadata }>('../assets/stories/*.{jpg,jpeg,png}', { eager: true });

export function storyImage(file: string): ImageMetadata {
  const mod = images[`../assets/stories/${file}`];
  if (!mod) throw new Error(`Story image not found in src/assets/stories: ${file}`);
  return mod.default;
}
