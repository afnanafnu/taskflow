import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                // Task pages
                'resources/css/tasks/task-create.css',
                'resources/js/tasks/task-create.js',
            ],
            refresh: true,
        }),
    ],
});