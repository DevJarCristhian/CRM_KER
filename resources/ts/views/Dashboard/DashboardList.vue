<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
    path: string;
}>();

</script>

<template>
    <div class="h-full">
        <div class="w-full bg-white dark:bg-gray-900 shadow h-12 p-3 rounded-md mb-4 font-semibold">
            Dashboard
        </div>

        <div class="w-full h-[91%] bg-white dark:bg-gray-900 shadow p-3 rounded-md">

        </div>
    </div>
</template>

<style scoped></style>