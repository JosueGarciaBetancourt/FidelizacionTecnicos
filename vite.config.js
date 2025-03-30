import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

// Obtener todos los archivos CSS en resources/css/
const cssFiles = fs.readdirSync(path.resolve(__dirname, 'resources/css'))
    .filter(file => file.endsWith('.css'))  // Filtrar solo archivos .css
    .map(file => `resources/css/${file}`);  // Agregar la ruta completa

// Agregar también los archivos JS manualmente
const jsFiles = [
    'resources/js/app.js'
];

export default defineConfig({
    plugins: [
        laravel({
            input: [...cssFiles, ...jsFiles], // Combinar CSS y JS
            refresh: true,
        }),
    ],
});


// Copiar estilos css de resource/css a public/css
// cp -r resources/css/* public/css/
