<script setup lang="ts">
import { VForm } from 'vuetify/components'
import type { RegisterResponse } from '@/@fake-db/types'
import { useAppAbility } from '@/plugins/casl/useAppAbility'
import axios from '@axios'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import { alphaDashValidator, emailValidator, requiredValidator,confirmedValidator } from '@validators'

const refVForm = ref<VForm>()
const name = ref('')
const email = ref('')
const password = ref('')
const confirmed = ref('')
const privacyPolicies = ref(true)

// Router
const route = useRoute()
const router = useRouter()

// Ability
const ability = useAppAbility()

// Form Errors
const errors = ref<Record<string, string | undefined>>({
  name: undefined,
  email: undefined,
  password: undefined,
})

const register = () => {
  const headers = { 
    "Language": "en",
  };
  axios.post<RegisterResponse>('/api/v1/register', {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: confirmed.value,
  },{headers})
    .then(r => {
      const { accessToken, userData, userAbilities } = r.data
      // Redirect to `to` query if exist or redirect to index route
      router.replace(route.query.to ? String(route.query.to) : '/login')

      return null
    })
    .catch(e => {
      const { errors: formErrors } = e.response.data
      // errors.value = formErrors
      let res = e.response.data.data;
      for (const key in res) {
        for (const e_key in errors.value) {
          if(key == e_key) {
            errors.value[e_key] = res[key][0];
          }
        
        }
      }
    })
}




const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)

const onSubmit = () => {
  refVForm.value?.validate()
    .then(({ valid: isValid }) => {
      if (isValid)
      // errors.value.password = '';
        register();
    })
}
</script>

<template>
  <VRow
    no-gutters
    class="auth-wrapper"
  >
    <VCol
      lg="8"
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
      lg="4"
      class="d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-4"
      >
        <VCardText>
          <VNodeRenderer
            :nodes="themeConfig.app.logo"
            class="mb-6"
          />
          <h5 class="text-h5 font-weight-semibold mb-1">
            Welcome to {{ themeConfig.app.title }}
          </h5>
          <p class="mb-0">
            Please sign-in to your account and start the adventure
          </p>
        </VCardText>
        <ShareSnackbarTransition />
        <VCardText>
          <VForm
            ref="refVForm"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <!-- name -->
              <VCol cols="12">
                <VTextField
                  v-model="name"
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="Name"
                  :error-messages="errors.name"
                />
              
              </VCol>
            

              <!-- email -->
              <VCol cols="12">
                <VTextField
                  v-model="email"
                  :rules="[requiredValidator, emailValidator]"
                  label="Email"
                  type="email"
                  :error-messages="errors.email"
                />
              
              </VCol>
            
              <!-- password -->
              <VCol cols="12">
                <VTextField
                  v-model="password"
                  :rules="[requiredValidator]"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  :error-messages="errors.password"
                />
              
              </VCol>
                 <!-- confirmed password -->
              <VCol cols="12">
                <VTextField
                  v-model="confirmed"
                  :rules="[requiredValidator,confirmedValidator(confirmed, password)]"
                  label="Confirmed"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                  :error-messages="errors.password"
                />
              </VCol>
              

                <div class="d-flex align-center mt-2 mb-4">
                  <VCheckbox
                    id="privacy-policy"
                    v-model="privacyPolicies"
                    inline
                  />
                  <VLabel
                    for="privacy-policy"
                    class="pb-1"
                    style="opacity: 1;"
                  >
                    <span class="me-1">I agree to</span>
                    <a
                      href="javascript:void(0)"
                      class="text-primary"
                    >privacy policy & terms</a>
                  </VLabel>
                </div>

                <VBtn
                  block
                  type="submit"
                >
                  Sign up
                </VBtn>
             
              <!-- create account -->
              <VCol
                cols="12"
                class="text-center text-base"
              >
                <span>Already have an account?</span>
                <RouterLink
                  class="text-primary ms-2"
                  :to="{ name: 'login' }"
                >
                  Sign in instead
                </RouterLink>
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
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
