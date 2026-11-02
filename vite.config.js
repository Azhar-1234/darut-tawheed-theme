import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  define: { 'process.env.NODE_ENV': JSON.stringify('production') },
  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    lib: { entry: 'src/main.jsx', name: 'DTHome', formats: ['iife'], fileName: () => 'home.js', cssFileName: 'home' },
  },
});
