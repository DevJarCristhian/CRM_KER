<script lang="ts" setup>
import { onMounted, onUpdated, ref } from 'vue'
import { Store } from '@/services/interfaces/access/users.interfaces';
import usersServices from '@/services/access/users.services'
import { useMessage, type FormInst, type FormRules } from 'naive-ui'

const props = defineProps({
    show: Boolean,
    items: Object as () => Store,
});

const emit = defineEmits(['close', 'refresh']);

const message = useMessage()
const formRef = ref<FormInst | null>(null)
const loading = ref<boolean>(false)
const formData = ref<Store>(props.items as Store)
const roles = ref<{ label: string, value: number }[]>([])

const rules: FormRules = {
    name: [
        { required: true, message: "El nombre es Requerido", trigger: "blur" }
    ],
    email: [
        { required: true, message: "Correo Requerido", trigger: "blur" },
        { type: "email", message: "Ingresa un correo valido", trigger: "blur" }
    ],
    role_id: [
        { required: true, type: "number", message: "Rol Requerido", trigger: "blur" }
    ],
    password: [
        { required: true, message: "Contraseña Requerida", trigger: "blur", },
        { min: 6, message: "La constraseña debe ser mayor a 6 caracteres", trigger: "blur" }
    ],
}

onMounted(() => {
    getRoles()
})

onUpdated(() => {
    if (props.show == true) {
        formData.value = props.items as Store;
    }
})

const getRoles = async () => {
    const response = await usersServices.getRoles()
    roles.value = response.data
}

const handleSubmit = async () => {
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const response = formData.value.id ?
                await usersServices.update(formData.value.id, formData.value) : await usersServices.store(formData.value)
            console.log(response)
            emit('refresh')
            loading.value = false
            closeModal()
            message.success(formData.value.id ? 'Usuario Actualizado' : 'Usuario Creado')
        } else {
            message.error('Campos Requeridos')
        }
    })
}

const closeModal = () => {
    emit("close");
    formRef.value?.restoreValidation()
};
</script>
<template>
    <n-modal :show="show" :on-close="closeModal" @esc="closeModal()" preset="card" :mask-closable="false"
        :title="formData.id ? 'Editar Usuario' : 'Crear Usuario'" close-on-esc style="width: 500px;"
        :auto-focus="false">

        <n-form ref="formRef" :model="formData" :rules="rules" label-placement="left" label-width="120"
            require-mark-placement="right-hanging" class="flex flex-col gap-1">
            <n-form-item label="Nombres" path="name">
                <n-input v-model:value="formData.name" placeholder="Ingrese Nombres" />
            </n-form-item>
            <n-form-item label="Email" path="email">
                <n-input v-model:value="formData.email" placeholder="Ingrese Email" />
            </n-form-item>
            <n-form-item label="Rol" path="role_id">
                <n-select v-model:value="formData.role_id" :options="roles" placeholder="Selecciona Rol" />
            </n-form-item>
            <n-form-item label="Contraseña" :path="formData.id ? '' : 'password'">
                <n-input v-model:value="formData.password" type="password" placeholder="Ingrese Contraseña"
                    show-password-on="click" />
            </n-form-item>
            <n-form-item label="Estado">
                <n-switch :disabled="formData.id ? false : true" v-model:value="formData.status" />
            </n-form-item>
            <!-- <pre>{{ JSON.stringify(items, null, 2) }}</pre> -->
        </n-form>
        <template #footer>
            <div class="flex gap-2 justify-end">
                <n-button @click="closeModal()" quaternary>Cancelar</n-button>
                <n-button type="primary" :loading="loading" @click="handleSubmit" class="min-w-[25%]">Guardar</n-button>
            </div>
        </template>
    </n-modal>
</template>
