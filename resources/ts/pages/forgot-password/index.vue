<script setup lang="ts">
import loginimg from '@images/logos/logo.png'
import axios from 'axios'
import { VForm } from 'vuetify/components'
import { emailValidator,requiredValidator } from '@/@core/utils/validators'

const refVForm = ref<VForm>()
  const email = ref('')



const errors = ref<Record<string, string | undefined>>({
   email: undefined,
   message:undefined
})
const forgotPassword = () => {
  axios.post<any>('/api/v1/password/forgot/',{
    email: email.value,
  })
    .then(r => {

      console.log(r);
      errors.value.message =r.data.message;
      return null
    })
    .catch(e => {
      console.error(e.response.data);
      let res = e.response.data;
     
      
      if(Object.keys(res.data).length === 0) {
        console.log(res.message);
        errors.value.message =res.message;
        return
      }else {
      for (const key in res.data) {
        for (const e_key in errors.value) {
          if(key == e_key) {
            errors.value[e_key] = res.data[key][0];
          }
        
        }
      }
    }
    })
}
const onSubmit = () => {
  refVForm.value?.validate()
    .then(({ valid: isValid }) => {
      if (isValid)
      // errors.value.password = '';
      forgotPassword()
    })
}
</script>

<template>
  <VRow
    class="auth-wrapper"
    no-gutters
  >
    <VCol
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
        :max-width="500"
        class="mt-12 mt-sm-0 pa-4"
      >
        <VCardText class="text-center">
          <!-- <VNodeRenderer
            :nodes="themeConfig.app.logo"
            class="mb-6"
          /> -->
          <img :src="loginimg" />
          <h5 class="text-h5 font-weight-semibold mb-1">
            Forgot Password?
          </h5>
          <p class="mb-0">
            Enter your email and we'll send you instructions to reset your password
          </p>
        </VCardText>

        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onSubmit">
            <VRow>
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
              <div >{{errors.message }}</div>

              <!-- Reset link -->
              <VCol cols="12">
                <VBtn
                  block
                  type="submit"
                >
                  Send Reset Link
                </VBtn>
              </VCol>

              <!-- back to login -->
              <VCol cols="12">
                <RouterLink
                  class="d-flex align-center justify-center"
                  :to="{ name: 'login' }"
                >
                  <VIcon
                    icon="tabler-chevron-left"
                    class="flip-in-rtl"
                  />
                  <span>Back to login</span>
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
