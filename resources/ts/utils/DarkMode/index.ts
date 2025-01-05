import { ref } from "vue";
const theme = ref(localStorage.getItem("theme") === "dark");

const toggleDarkMode = () => {
    if (theme.value) {
        document.documentElement.classList.add("dark");
        document.documentElement.classList.remove("light");
    } else {
        document.documentElement.classList.remove("dark");
        document.documentElement.classList.add("light");
    }
    localStorage.setItem("theme", theme.value ? "dark" : "light");
    theme.value = !theme.value;
};

export const useDarkMode = () => {
    return {
        toggleDarkMode,
        theme,
    };
};

toggleDarkMode();
