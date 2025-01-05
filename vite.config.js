import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/ts/app.ts"],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/ts"),
        },
    },
    server: {
        https: true,
        proxy: {
            "/api": "https://crmker-production.up.railway.app", // Si estás usando API externas
        },
    },
});
