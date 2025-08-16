import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/tailwind-admin.css",
                "resources/js/main.js",
                "resources/css/libs/datatable.css",
                "resources/js/pages/movements/index.ts",
                "resources/js/pages/locker/index.ts",
                "resources/js/pages/resident/index.ts",
                "resources/js/layout.ts",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
