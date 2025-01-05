<script setup lang="ts">
import authServices from '@/services/auth.services';
import { authStores } from '@/store/auth';
import { FormInst, useMessage } from 'naive-ui';
import { ref } from 'vue';

const formRef = ref<FormInst | null>(null)
const loading = ref<boolean>(false)
const message = useMessage()
const store = authStores()
const form = ref<{ email: string, password: string }>({
  email: '',
  password: '',
})

const login = async (e: MouseEvent) => {
  e.preventDefault()
  formRef.value?.validate(async (errors) => {
    if (!errors) {
      try {
        loading.value = true
        const response = await authServices.login(form.value)
        // console.log("here", response);
        if (response.data.token) {
          localStorage.setItem('token', response.data.token)
          localStorage.removeItem("tokenRemoved");
          message.success('Bienvenido al Sistema')
          await store.getUser()

          setTimeout(() => {
            window.location.href = '/'
          }, 600)
        }
      } catch (error) {
        message.error("Usuario o contraseña incorrectos.")
      } finally {
        setTimeout(() => {
          loading.value = false
        }, 400)
      }
    }
    else {
      console.log(errors)
      message.error('Invalid')
    }
  })
}

const rules = {
  email:
    [
      { required: true, message: "Correo Requerido", trigger: 'blur' },
      { type: 'email', message: "Ingresa un correo valido", trigger: 'blur' }
    ],
  password: [
    { required: true, message: "Contraseña Requerida", trigger: "blur", },
    { min: 6, message: 'La constraseña debe ser mayor a 6 caracteres', trigger: 'blur' }
  ],

};
</script>

<template>
  <div class="flex h-screen w-screen">
    <div
      class="flex justify-center items-center sm:w-2/4 w-full bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-600">
      <div class="grid gap-3 w-full px-4 md:px-[20%]">
        <div class="flex justify-center">
          <img width="50px" src="../assets/short.webp" alt="">
        </div>
        <span class="text-xl text-center font-semibold">Iniciar Sesion</span>
        <span class="text-md opacity-70 text-center -mt-3 mb-4">Bienvenido al Sistema</span>

        <n-form ref="formRef" :rules="rules" v-model:model="form">
          <span> Correo </span>
          <n-grid :cols="1">
            <n-form-item-gi path="mail" class="-mt-5">
              <n-input v-model:value="form.email" placeholder="correo@ejemplo.com" @keydown.enter="login">
                <template #prefix>
                  <j-icon name="profile" w="w-5" />
                </template>
              </n-input>
            </n-form-item-gi>
          </n-grid>

          <span> Contraseña </span>
          <n-grid :cols="1">
            <n-form-item-gi path="password" class="-mt-5">
              <n-input v-model:value="form.password" type="password" placeholder="ingrese contraseña"
                show-password-on="click" @keydown.enter="login">
                <template #prefix>
                  <j-icon name="pass" w="w-5" />
                </template>
              </n-input>
            </n-form-item-gi>
          </n-grid>
          <n-form-item>
            <n-button type="primary" :loading="loading" class="w-full -mt-5" @click="login">Iniciar
              Sesion</n-button>
          </n-form-item>
        </n-form>
      </div>
    </div>
    <div class="w-full hidden sm:block bg-white dark:bg-gray-900">
      <div class="flex justify-center items-center h-full">
        <img width="300px" src="../assets/logo.webp" alt="">
      </div>
    </div>
  </div>
</template>

<style scoped></style>
<!-- bg-zinc-50 dark:bg-[#141D2C] -->