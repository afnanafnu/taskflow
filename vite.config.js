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
                'resources/css/tasks/task-show.css',
                'resources/css/tasks/task-index.css',

                // project pages
                'resources/css/projects/projects-index.css',
                'resources/css/projects/project-show.css',
                'resources/css/projects/project-create-form.css',
                'resources/css/projects/project-edit-form.css',

            ],
            refresh: true,
        }),
    ],
});