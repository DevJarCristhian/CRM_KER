<script setup lang="ts">
import { defineAsyncComponent, h, onMounted, ref } from 'vue';
import usersServices from '@/services/access/users.services'
import { Get, Params, Store } from '@/services/interfaces/access/users.interfaces';
import { NButton, NTag } from 'naive-ui';
import dayjs from 'dayjs';
import JIcon from '@/components/JIcon.vue';
// import 'dayjs/locale/es' 
// dayjs.locale('es')

const add = defineAsyncComponent(() => import('@/views/Access/Users/modals/addUser.vue'))

const ddd = dayjs()
// console.log(ddd.format('dddd D  MMMM')) // jueves 2 de enero


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
    perPage: 25,
    search: null,
    status: null,
})
const userData = ref<Store>({
    name: '',
    email: '',
    password: null,
    role_id: null,
    status: true
})
const pagination = ref({
    page: 1,
    pageCount: 1,
    pageSize: 25,
    total: 0,
    prefix() {
        return `Total ${pagination.value.total} Items de ${pagination.value.pageCount} paginas`
    },
    onUpdatePage(page: any) {
        params.value.page = page
        pagination.value.page = page
        getUsers()
    }
})

onMounted(() => {
    getUsers()
})

const getUsers = async () => {
    loading.value = true
    const response = await usersServices.get(params.value)
    data.value = response.data.data
    // console.log(response);
    pagination.value.pageCount = response.data.last_page
    pagination.value.total = response.data.total
    loading.value = false
}

const userReset = () => {
    userData.value = {
        name: '',
        email: '',
        role_id: null,
        status: true
    }
    showModal.value = true
}

const setItems = (item: Get) => {
    userData.value.id = item.id
    userData.value.name = item.name
    userData.value.email = item.email
    userData.value.role_id = item.role.id
    userData.value.status = item.status == 'active' ? true : false
    showModal.value = true
}

const formatDate = (date: string) => {
    if (!date || !dayjs(date).isValid()) { return '-'; }
    return dayjs(date).format('YYYY-MM-DD')
}

const options = ref([
    {
        label: 'Estado',
        key: 'state'
    },
    {
        label: 'Rol',
        key: 'role'
    },
]);


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
        title: 'Nombres',
        key: 'name'
    },
    {
        title: 'Correo',
        key: 'email'
    },
    {
        title: 'Rol',
        key: 'role',
        render(row: any,) {
            return row.role.description
        }
    },
    {
        title: 'Estado',
        key: 'status',
        render(row: any) {
            return h(
                NTag,
                {
                    type: row.status == "active" ? 'success' : 'error',
                    bordered: false,
                    round: true,
                    size: 'small',
                },
                {
                    default: () => row.status == "active" ? 'Activo' : 'Inactivo'
                }
            )
        }
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
                    // style: {
                    //     height: '30px',
                    // },
                    // round: true,
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
        <add :show="showModal" :items="userData" @close="showModal = !showModal"
            @refresh="pagination.onUpdatePage(1)" />
        <div class="bg-white dark:bg-[#1E2838] shadow h-12 rounded mb-4 font-semibold p-2 px-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <span class="text-lg -mt-1">Usuarios</span>
                    <n-dropdown trigger="click" :options="options">
                        <div class="flex items-center gap-1 cursor-pointer select-none">
                            <j-icon w="w-[16px]" name="filter" />
                            <span>Filtros</span>
                            <j-icon w="w-[14px]" name="down" />
                        </div>
                    </n-dropdown>
                </div>
                <div class="flex items-center gap-2">
                    <n-button size="small" type="primary" @click="userReset">
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