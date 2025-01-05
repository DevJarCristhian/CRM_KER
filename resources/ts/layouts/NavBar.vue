<script setup lang="ts">
import JIcon from '@/components/JIcon.vue';
import { useDarkMode } from '../utils/DarkMode';
import { globalActions } from '@/store/actions';
import { h, ref, toRefs } from 'vue';
import { useRouter } from 'vue-router';
import authServices from '@/services/auth.services';
import { authStores } from '@/store/auth';

const { toggleDarkMode, theme } = useDarkMode();
const { user } = toRefs(authStores())

const store = globalActions();
const router = useRouter();
const renderIcon = (icon: any) => {
    return () => {
        return h(JIcon, { name: icon, w: 'w-4' });
    }
};

const options = ref([
    {
        label: theme.value ? 'Modo Oscuro' : 'Modo Claro',
        key: 'theme',
        icon: theme.value ? renderIcon("moon") : renderIcon("sun")
    },
    {
        label: 'Cerrar sesión',
        key: 'logout',
        icon: renderIcon("logout")
    }
])

const handleSelect = async (key: string) => {
    if (key === 'logout') {
        await authServices.logout()
        localStorage.removeItem('token')
        router.push('/login');
    }
    if (key === 'theme') {
        toggleDarkMode()
        options.value[0].icon = theme.value ? renderIcon("moon") : renderIcon("sun")
        options.value[0].label = theme.value ? 'Modo Oscuro' : 'Modo Claro'
    }
}

const optionsNoty = ref([
    {
        label: '3 Mensajes',
        key: '1',
        icon: renderIcon("profile")
    },
    {
        label: '2 Llamadas',
        key: '2',
        icon: renderIcon("profile")
    },
    {
        label: '1 Mensajes',
        key: '3',
        icon: renderIcon("profile")
    },
])

const offset = [-5, -1] 
</script>

<template>
    <div class="flex justify-between h-14 w-full p-2 px-4 bg-white dark:bg-gray-900 drop-shadow-md z-10">
        <div class="flex items-center">
            <button @click="store.setCollapsed()"
                class="opacity-70 hover:bg-slate-200/60 dark:hover:bg-gray-800 rounded-md p-2">
                <j-icon name="menu" />
            </button>
            <span class="">
                <img width="120px" src="../assets/logo.webp" alt="">
            </span>
        </div>

        <div class="flex items-center gap-1">
            <n-dropdown trigger="click" :show-arrow="true" :options="optionsNoty" @select="handleSelect">
                <button class=" hover:bg-slate-200/60 dark:hover:bg-gray-800 rounded-md p-2">
                    <n-badge :value="3" :max="15" :offset="offset">
                        <j-icon name="bell" class="opacity-60" />
                    </n-badge>
                </button>
            </n-dropdown>

            <n-dropdown trigger="click" :show-arrow="true" :options="options" @select="handleSelect">
                <div class="flex items-center gap-2 cursor-pointer select-none">
                    <div class="opacity-70 bg-slate-200/60 dark:bg-gray-800 rounded-md p-1.5">
                        <j-icon name="profile" />
                    </div>
                    <div class="flex items-center">
                        <div class="grid grid-cols-1 items-center -space-y-1  -mt-1">
                            <div class="text-sm font-medium">
                                {{ user.name }}
                            </div>
                            <div class="text-xs opacity-55">
                                {{ user.role }}
                            </div>
                        </div>
                        <div class="opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 3.586L17.414 9L16 10.414l-4-4l-4 4L6.586 9zm-4 10l4 4l4-4L17.414 15L12 20.414L6.586 15z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </n-dropdown>
        </div>
    </div>
</template>

<style scoped></style>