import { resolve } from 'path'
import { defineConfig } from 'vite';

const ROOT = resolve('../../../')
const BASE = __dirname.replace(ROOT, '');

export default defineConfig({
  base: process.env.NODE_ENV === 'production' ? `${BASE}/dist/` : BASE,
  build: {
    manifest: true,
    assetsDir: '.',
    outDir: 'dist',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        scripts: resolve(__dirname,'assets/scripts/main.js'),
        styles: resolve(__dirname,'assets/styles/main.scss'),
      },
      output: {
        entryFileNames: '[name].js',
        assetFileNames: '[name].[ext]',
      },
    },
  },
  plugins: [
    {
      name: 'wp',
      handleHotUpdate({ file, server }) {
        if (file.endsWith('.php') || file.endsWith('.js') || file.endsWith('.scss')) {
          server.hot.send({ type: 'update' });
        }
      },
    },
  ],
});