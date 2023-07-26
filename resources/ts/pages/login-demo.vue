<script setup lang="ts">
import { VForm } from 'vuetify/components'
// import type { LoginResponse } from '@/@fake-db/types'
import { useAppAbility } from '@/plugins/casl/useAppAbility'
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import axios from '@axios'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { emailValidator, requiredValidator } from '@validators'


import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import loginimg from '@images/logos/logo.png'


const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

const isPasswordVisible = ref(false)

const route = useRoute()
const router = useRouter()

const ability = useAppAbility()

const errors = ref<Record<string, string | undefined>>({
  email: undefined,
  password: undefined,
})

const refVForm = ref<VForm>()
const email = ref('')
const password = ref('')
const rememberMe = ref(false)

const login = () => {
  axios.post<any>('/auth/login', { email: email.value, password: password.value })
    .then(r => {
      // return
      const { accessToken, userData, userAbilities } = r.data

      localStorage.setItem('userAbilities', JSON.stringify(userAbilities))
      ability.update(userAbilities)

      localStorage.setItem('userData', JSON.stringify(userData))
      localStorage.setItem('accessToken', JSON.stringify(accessToken))

      // Redirect to `to` query if exist or redirect to index route
      router.replace(route.query.to ? String(route.query.to) : '/')
    })
    .catch(e => {
      const { errors: formErrors } = e.response.data

      errors.value = formErrors
      console.error(e.response.data)
    })
}

const onSubmit = () => {
  refVForm.value?.validate()
    .then(({ valid: isValid }) => {
      if (isValid)
        login()
    })
}
</script>

<template>
  <VRow
    no-gutters
    class="auth-wrapper"
  >
    <VCol
        cols="12"
        lg="7"
        class="d-none d-lg-flex"
    >
        <div class="position-relative auth-bg rounded-lg w-100 ma-8 me-0">
            <div class="d-flex align-center justify-center w-100 h-100">
                <div class="pl-4">
                    <h6 class="text-h6 font-weight-semibold mb-1">
                        {{ $t("login_page_text1") }}
                    </h6>
                    <h3 class="text-h3 font-weight-semibold mb-1">
                        {{ $t("login_page_text2") }}
                    </h3>
                    <p>
                        {{ $t("login_page_text3") }}
                    </p>
                </div>
            </div>
        </div>
    </VCol>

    <VCol
        cols="12"
        lg="5"
        class="login-center"
    >
        <VCard
            flat
            :max-width="450"
            class=" "
        >
        <VCardText class="text-center">
            <img :src="loginimg" />
            <h5 class="text-h5 font-weight-semibold mt-4">{{ $t("login_page_right_text1")}}</h5>
        </VCardText>
        <VCardText>
            <VForm
                ref="refVForm"
                @submit.prevent="onSubmit"
            >
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <VTextField
                  v-model="email"
                  label="Enter Username"
                  type="email"
                  :rules="[requiredValidator, emailValidator]"
                  :error-messages="errors.email"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <VTextField
                  v-model="password"
                  label="Enter Password"
                  :rules="[requiredValidator]"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :error-messages="errors.password"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4">
                  <VCheckbox
                    v-model="rememberMe"
                    label="Remember me"
                  />
                  <RouterLink
                    class="text-primary ms-2 mb-1"
                    :to="{ name: 'forgot-password' }"
                  >
                    Forgot Password?
                  </RouterLink>
                </div>

                <VBtn
                  block
                  type="submit"
                >
                  Sign In
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
<style lang="scss">

@use "@core-scss/template/pages/page-auth.scss";
  .login-center{
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 400px;
    margin: 0 auto;
  }
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
