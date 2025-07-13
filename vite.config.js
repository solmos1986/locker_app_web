import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/tailwind-admin.css",
                "resources/js/app.js",
                "resources/js/libs/jquery.js",
                "resources/js/libs/datatable.js",
                "resources/js/libs/datatabletailwind.js",
                "resources/css/libs/datatable.css",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
