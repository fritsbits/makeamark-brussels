import { defineCollection } from 'astro:content';
import { glob, file } from 'astro/loaders';
import { z } from 'astro/zod';

const editions = defineCollection({
  loader: glob({ pattern: '*.md', base: './src/content/editions' }),
  schema: z.object({
    year: z.number().int(),
    kicker: z.string(),
    title: z.string(),
    dates: z.string(),
    startDate: z.coerce.date(),
    endDate: z.coerce.date(),
    status: z.enum(['open', 'closed', 'selected', 'done']),
    applyUrl: z.url().optional(),
    case: z.object({ name: z.string(), url: z.url() }),
    photo: z.string(),
    photoAlt: z.string(),
    callToAction: z.string(),
  }).refine((d) => d.status !== 'open' || !!d.applyUrl, { message: 'status: open requires applyUrl', path: ['applyUrl'] }),
});

const organisations = defineCollection({
  loader: file('./src/content/data/organisations.json'),
  schema: z.object({
    id: z.string(),
    name: z.string(),
    url: z.url().optional(),
  }),
});

const faq = defineCollection({
  loader: glob({ pattern: '*.md', base: './src/content/faq' }),
  schema: z.object({
    question: z.string(),
    order: z.number().int(),
  }),
});

export const collections = { editions, organisations, faq };
