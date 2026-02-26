import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        // ... plugin laravel kamu
    ],
    server: {
        host: '0.0.0.0', // Tambahkan baris ini
        hmr: {
            host: '192.168.1.15' // Ganti dengan IPv4 laptopmu
        }
    }
});