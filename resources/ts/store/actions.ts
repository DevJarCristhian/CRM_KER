import { defineStore } from "pinia";

export const globalActions = defineStore("globalActions", {
    state: () => ({ collapsed: true, toggleDark: false }),
    getters: {
        getCollapsed: (state) => (state.collapsed = !state.collapsed),
    },
    actions: {
        setCollapsed() {
            this.collapsed = !this.collapsed;
        },
        // setDarkMode() {
        //     this.darkMode = !this.darkMode;
        // }
    },
});
