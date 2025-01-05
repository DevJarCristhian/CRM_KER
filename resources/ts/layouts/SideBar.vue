<script setup lang="ts">
import { globalActions } from '../store/actions';
import { RouterLink, useRoute } from "vue-router";
import navigations from '@/router/navigations';
import { h, ref, toRefs } from 'vue';
import JIcon from '@/components/JIcon.vue';
import { authStores } from '@/store/auth';

const route = useRoute();
const store = globalActions();
const { user } = toRefs(authStores())
const { collapsed } = toRefs(store);

const activeKey: string = (route.name as string) || "";
const selectedKeys = ref<string>(activeKey);

const renderMenuIcon = (option: any) => {
    if (!!option.i) {
        return h(JIcon, { name: option.i, });
    }
    return null;
};

const renderMenuLabel = (option: any) => {
    if (!!option.href) {
        return h(RouterLink, { to: option.href }, () =>
            h("span", { style: "width:100%", }, { default: () => option.label })
        );
    }
    return option.label;
};

const changePath = (key: string) => {
    if (key === 'home') {
        // window.location.reload();
        window.location.href = '/';
    }
};

</script>

<template>
    <div class="bg-white dark:bg-gray-900 dark:border-r dark:border-gray-800/80 drop-shadow h-full hidden md:block"
        :class="{ 'w-44': !collapsed, 'w-16': collapsed }">
        <n-menu :render-label="renderMenuLabel" :render-icon="renderMenuIcon" :collapsed="collapsed"
            :collapsed-width="64" :collapsed-icon-size="22" :options="navigations" accordion
            v-model:value="selectedKeys" @update:value="changePath" />
    </div>
</template>

<style scoped></style>