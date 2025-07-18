// @ts-check
import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';

// https://astro.build/config
export default defineConfig({
	integrations: [
		starlight({
			title: 'TenantForge',
			social: [{ icon: 'github', label: 'GitHub', href: 'https://github.com/tenant-forge/tenant-forge-starter-kit' }],
			sidebar: [
                {
                    label: 'Getting Started',
                    items: [
                        // Each item here is one entry in the navigation menu.
                        { label: 'Introduction', slug: 'getting-started/introduction' },
                        { label: 'Installation', slug: 'getting-started/installation' },
                    ],
                },
				{
					label: 'Guides',
					items: [
						// Each item here is one entry in the navigation menu.
						{ label: 'Example Guide', slug: 'guides/example' },
					],
				},
				{
					label: 'Reference',
					autogenerate: { directory: 'reference' },
				},
			],
		}),
	],
});
