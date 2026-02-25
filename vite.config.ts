import { resolve } from 'path'
import { defineConfig } from 'vite'
import dts from 'vite-plugin-dts'

export default defineConfig({
    build: {
        outDir: 'playwright/dist',
        lib: {
            entry: resolve(__dirname, 'playwright/src/index.ts'),
            name: 'LaravelPlaywright',
            fileName: 'laravel-playwright',
        },
        rollupOptions: {
            external: ['@playwright/test'],
            output: {
                globals: {
                    '@playwright/test': 'PlaywrightTest',
                },
            },
        },
    },
    plugins: [dts({ include: ['playwright/src'] })],
})
