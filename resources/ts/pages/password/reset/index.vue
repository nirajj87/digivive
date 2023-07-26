<script setup lang="ts">
import { confirmedValidator, requiredValidator } from '@validators';

import { VForm } from 'vuetify/components'
import loginimg from '@images/logos/logo.png'
import { useAppAbility } from '@/plugins/casl/useAppAbility'
import axios from '@axios'

const refVForm = ref<VForm>()
// const old_password = ref('');
const password = ref('');
const confirm_password = ref('');

const isPasswordVisible = ref(false);
const isConfirmPasswordVisible = ref(false);

const errors = ref<Record<string, string | undefined>>({
   password: undefined,
   message:undefined
})


let email:any;
let token:any;


// Router
const route = useRoute()
const router = useRouter()

// Ability
const ability = useAppAbility()

let is_error=true;

// Form Errors
let moduleData = ref()



const resetPassword = () => {
  axios.post<any>('/api/v1/password/reset/',{
    email: email,
    token: token,
    password: password.value,
    password_confirmation: confirm_password.value,
  })
    .then(r => {

      console.log(r);
      router.replace('/login')
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
watchEffect(()=>{
  token = route.query.token;
  email = route.query.email;
})
const onSubmit = () => {
  refVForm.value?.validate()
    .then(({ valid: isValid }) => {
      if (isValid)
      // errors.value.password = '';
        resetPassword()
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


        <img :src="loginimg" />

          <h5 class="text-h5 font-weight-semibold mb-1">
            Reset Password
          </h5>
          <p class="mb-0">Please change your password</p>
        </VCardText>

        <VCardText>
          <VForm ref="refVForm"
            @submit.prevent="onSubmit">
            <VRow>
             

               <!-- new password -->
                <VCol cols="12">
                <VTextField
                  v-model="password"
                  label="New Password"
                  :rules="[requiredValidator]"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :error-messages="errors.password"
                  :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />
            </VCol>
                 <!-- confirm password -->
               <VCol cols="12">
                <VTextField
                  v-model="confirm_password"
                  label="Confrim Password"
                  :type="isConfirmPasswordVisible ? 'text' : 'password'"
                  :rules="[requiredValidator,confirmedValidator(confirm_password, password)]"
                  :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                  :error-messages="errors.message"
                  @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                />
            </VCol>
              <!-- Reset link -->
              <VCol cols="12">
                <VBtn
                  block
                  type="submit"
                >
                  Change Password
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
