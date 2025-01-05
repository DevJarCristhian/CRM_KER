<script setup lang="ts">
import { onMounted, ref, watch, } from 'vue';
import mainRouter from './router';
import loginRouter from './router/login';
import { useRouter } from 'vue-router';
import { useDarkMode } from './utils/DarkMode';
import { esAR, dateEsAR } from 'naive-ui'
import { light, dark } from './utils/Config';
import { authStores } from './store/auth';

const router = useRouter();
const isAuthenticated = ref(true);
const store = authStores();
const { theme } = useDarkMode();
const setTheme = ref(theme.value ? light : dark);

watch(theme, () => {
    setTheme.value = theme.value ? light : dark;
});

onMounted(() => {
    if (isAuthenticated.value) {
        store.getUser();
        mainRouter.getRoutes().forEach(route => { router.addRoute(route); });
    } else {
        loginRouter.getRoutes().forEach(route => { router.addRoute(route); });
        router.push('/login');
    }
});
</script>

<template>
    <div id="app">
        <n-config-provider :theme-overrides="setTheme" :locale="esAR" :date-locale="dateEsAR">
            <n-modal-provider>
                <n-message-provider>
                    <router-view />
                </n-message-provider>
            </n-modal-provider>
        </n-config-provider>
    </div>
</template>
