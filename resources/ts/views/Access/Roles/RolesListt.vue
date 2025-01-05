<script setup lang="ts">
import { defineAsyncComponent, h, onMounted, ref } from 'vue';
import rolesServices from '@/services/access/roles.services'
import { Get, Params, Store } from '@/services/interfaces/access/roles.interfaces';
import { NButton, NTag } from 'naive-ui';
import dayjs from 'dayjs';
import JIcon from '@/components/JIcon.vue';

const add = defineAsyncComponent(() => import('@/views/Access/Roles/modals/addRole.vue'))

const props = defineProps<{
    path: string;
}>();

// const actions = await useSectionStores.getActionsForSection(
//     props.section ?? ""
// );

// console.log(props.path);

const data = ref<Get[]>([])
const loading = ref<boolean>(false)
const showModal = ref<boolean>(false)
const params = ref<Params>({
    page: 1,
    perPage: 50,
    search: null,
    status: null,
})
const roleData = ref<Store>({
    description: '',
    permissions: []
})
const pagination = ref({
    page: 1,
    pageCount: 1,
    pageSize: 50,
    total: 0,
    prefix() {
        return `Total ${pagination.value.total} Items de ${pagination.value.pageCount} paginas`
    },
    onUpdatePage(page: any) {
        params.value.page = page
        pagination.value.page = page
        getRoles()
    }
})

onMounted(() => {
    getRoles()
})

const getRoles = async () => {
    loading.value = true
    const response = await rolesServices.get(params.value)
    data.value = response.data
    // console.log(response);
    // pagination.value.pageCount = response.data.last_page
    // pagination.value.total = response.data.total
    loading.value = false
}

const roleReset = () => {
    roleData.value = {
        description: '',
        permissions: []
    }
    showModal.value = true
}

const setItems = (item: Get) => {
    console.log(item);

    roleData.value.id = item.id
    roleData.value.description = item.description
    roleData.value.permissions = item.permissions
    showModal.value = true
}

const formatDate = (date: string) => {
    if (!date || !dayjs(date).isValid()) { return '-'; }
    return dayjs(date).format('YYYY-MM-DD')
}

const columns = ref([
    {
        title: '#',
        key: 'index',
        width: 50,
        align: 'center',
        render(row: any, index: number) {
            return index + 1
        }
    },
    {
        title: 'Description',
        key: 'description'
    },
    {
        title: 'Fecha de creación',
        key: 'created_at',
        render(row: any) {
            return formatDate(row.created_at)
        }
    },
    {
        title: 'Fecha de actualización',
        key: 'updated_at',
        render(row: any) {
            return formatDate(row.updated_at)
        }
    },
    {
        title: '',
        key: 'actions',
        width: 150,
        align: 'center',
        render(row: any) {
            return h(
                NButton,
                {
                    size: 'tiny',
                    strong: true,
                    secondary: true,
                    onClick: () => {
                        setItems(row)
                    }
                },
                {
                    default: () => h(JIcon, { name: 'edit', w: 'w-3' })
                }
            )
        }
    }
])

</script>

<template>
    <div>
        <add :show="showModal" :items="roleData" @close="showModal = !showModal"
            @refresh="pagination.onUpdatePage(1)" />
        <div class="bg-white dark:bg-[#1E2838] shadow h-12 rounded mb-4 font-semibold p-2 px-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <span class="text-lg -mt-1">Roles</span>
                </div>
                <div class="flex items-center gap-2">
                    <n-button size="small" type="primary" @click="roleReset">
                        <j-icon w="w-[14px]" name="add" />
                        Nuevo
                    </n-button>
                    <button @click="pagination.onUpdatePage(1)"
                        class="opacity-70 w-7 h-7 flex justify-center items-center hover:bg-slate-200/60 dark:hover:bg-[#141D2C] rounded-md">
                        <j-icon w="w-[12px]" name="refresh" />
                    </button>
                    <button
                        class="opacity-70 w-7 h-7 flex justify-center items-center hover:bg-slate-200/60 dark:hover:bg-[#141D2C] rounded-md">
                        <j-icon w="w-[18px]" name="export" />
                    </button>

                    <n-input style="width: 200px" placeholder="Buscar..." v-model:value="params.search"
                        @keydown.enter="pagination.onUpdatePage(1)">
                        <template #prefix>
                            <j-icon w="w-[14px]" name="search" />
                        </template>
                    </n-input>
                </div>
            </div>
        </div>

        <n-data-table remote striped :columns="columns" :loading="loading" :data="data" :pagination="pagination"
            size="small" min-height="70vh" max-height="70vh" :scroll-x="1000">
        </n-data-table>
    </div>
</template>

<style scoped></style>