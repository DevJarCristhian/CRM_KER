import type { App } from "vue";
import JIcon from "@/components/JIcon.vue";

export const registerGlobalComponents = (app: App) => {
    app.component("JIcon", JIcon);
};
