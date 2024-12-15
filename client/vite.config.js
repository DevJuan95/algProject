import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import TanStackRouterVite from "@tanstack/router-plugin/vite";

// https://vite.dev/config/
export default defineConfig({
  plugins: [
      react(),
      TanStackRouterVite(),
  ],
  server: {
    host: true,
    port: 5173,
    strictPort:true,
    watch: {
      usePolling: true,
    },
    cors: {
      origin: 'http://webserver:80',
      credentials: true,
    },
    build: {
      assetsDir: './',
    },
    base: './',
  },
})
