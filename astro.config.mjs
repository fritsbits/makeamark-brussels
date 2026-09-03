import { defineConfig, fontProviders } from 'astro/config';
import sitemap from '@astrojs/sitemap';

export default defineConfig({
  site: 'https://letsmakeamark.brussels',
  integrations: [sitemap()],
  image: { layout: 'constrained' },
  fonts: [
    {
      provider: fontProviders.bunny(),
      name: 'Big Shoulders Display',
      cssVariable: '--font-display',
      weights: [300, 600],
      styles: ['normal'],
      subsets: ['latin', 'latin-ext'],
      fallbacks: ['Impact', 'sans-serif'],
      display: 'swap',
    },
    {
      provider: fontProviders.bunny(),
      name: 'Archivo',
      cssVariable: '--font-body',
      weights: [400, 600],
      styles: ['normal'],
      subsets: ['latin', 'latin-ext'],
      fallbacks: ['Helvetica Neue', 'Arial', 'sans-serif'],
      display: 'swap',
    },
  ],
});
