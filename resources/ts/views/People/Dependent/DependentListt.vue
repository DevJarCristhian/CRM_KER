<script setup lang="ts">
import { defineAsyncComponent, h, onMounted, ref } from 'vue';
import dependentServices from '@/services/people/dependent.services'
import { Get, Params, Store } from '@/services/interfaces/people/dependent.interfaces';
import { NButton, NTag } from 'naive-ui';
import dayjs from 'dayjs';
import JIcon from '@/components/JIcon.vue';

const add = defineAsyncComponent(() => import('@/views/People/Dependent/modals/addDependent.vue'))

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
const dependentData = ref<Store>({
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
        getDependent()
    }
})

onMounted(() => {
    getDependent()
})

const getDependent = async () => {
    loading.value = true
    const response = await dependentServices.get(params.value)
    data.value = response.data.data
    // console.log(response);
    pagination.value.pageCount = response.data.last_page
    pagination.value.total = response.data.total
    loading.value = false
}

const dependentReset = () => {
    dependentData.value = {
        description: '',
        permissions: []
    }
    showModal.value = true
}

const setItems = (item: Get) => {
    // console.log(item);
    dependentData.value.id = item.id
    dependentData.value.description = item.description
    dependentData.value.permissions = item.permissions
    // showModal.value = true
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
        title: 'Nombre',
        key: 'firstName',
        render(row: any) {
            return row.firstName + ' ' + row.lastName
        }
    },
    // {
    //     title: 'Apellido',
    //     key: 'lastName'
    // },
    {
        title: 'Genero',
        key: 'gender',
        width: 90,
        render(row: any) {
            return h(NTag, {
                type: row.gender == 1 ? 'success' : 'error',
                size: 'small',
                bordered: false,
                round: true,
            }, {
                default: () => row.gender == 1 ? 'Masculino' : 'Femenino'
            })
        }
    },
    {
        title: 'Direccion',
        key: 'address'
    },
    {
        title: 'Correo',
        key: 'email'
    },
    {
        title: 'Telefono',
        key: 'phone',
        width: 120,
    },
    {
        title: 'Pais',
        key: 'country'
    },
    {
        title: 'Departamento',
        key: 'departmentId'
    },
    {
        title: 'Documento',
        key: 'documentNumber',
        width: 140,
    },
    {
        title: 'F.Nacimiento',
        key: 'birthDate',
    },
    {
        title: 'F.Inscripción',
        key: 'enrollmentDate'
    },
    // {
    //     title: 'Fecha Actualización',
    //     key: 'updatedAt'
    // },
    {
        title: '',
        key: 'actions',
        width: 50,
        align: 'center',
        render(row: any) {
            return h(
                NButton,
                {
                    size: 'small',
                    strong: true,
                    secondary: true,
                    onClick: () => {
                        setItems(row)
                    }
                },
                {
                    default: () => h(JIcon, { name: 'more', w: 'w-4' })
                }
            )
        }
    }
])

</script>

<template>
    <div>
        <add :show="showModal" :items="dependentData" @close="showModal = !showModal"
            @refresh="pagination.onUpdatePage(1)" />
        <div class="bg-white dark:bg-[#1E2838] shadow h-12 rounded mb-4 font-semibold p-2 px-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <span class="text-lg -mt-1">Dependientes</span>
                </div>
                <div class="flex items-center gap-2">
                    <n-button size="small" type="primary" @click="dependentReset">
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
            size="small" min-height="70vh" max-height="70vh" :scroll-x="1700">
        </n-data-table>
    </div>
</template>

<style scoped></style>