<script setup lang="ts">
import { useRoute } from "vue-router";
import List from '@/views/Dashboard/DashboardList.vue';

const route = useRoute();
const name: string = (route.name as string) || "";

</script>

<template>
  <List :path="name" />
</template>