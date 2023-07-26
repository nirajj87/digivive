<script setup lang="ts">
import { useAppAbility } from '@/plugins/casl/useAppAbility'
import axios from '@axios'
import ErrorModule from '../view/ErrorModule.vue';
import SuccessModule from '../view/SuccessModule.vue';

// import { ModuleParams } from "../view/types";

let user_id:any;
let token:any;
let expires:any;
let signature:any;
// Router
const route = useRoute()
const router = useRouter()

// Ability
const ability = useAppAbility()

let is_error=true;

// Form Errors
let moduleData = ref()


 let axiosParams = () => {
        const params = new URLSearchParams();
        params.append('expires', expires);
        params.append('signature', signature);
        return params;
    }
const verifyEmail = () => {
  axios.get<any>('/api/verify/'+user_id+'/'+token, {
    params:axiosParams()
  })
    .then(r => {

      console.log(r);
      is_error=false;
      moduleData.value = r.data
      return null
    })
    .catch(e => {
      moduleData.value = e.response.data.data
      console.error(e.response.data.data)
    })
}

watchEffect(()=>{
  token = route.params.token;
  expires = route.query.expires;
  signature = route.query.signature;
  verifyEmail();
})
</script>

<template>
  <VRow
    no-gutters
    class="auth-wrapper"
  >
    

    <VCol
      cols="12"
      lg="12"
      class="d-flex align-center justify-center"
    >
      <VCard
        flat
        :max-width="500"
        class="mt-12 mt-sm-0 pa-4"
      >

      <SuccessModule :module-data="moduleData" v-if="!is_error"/>
        
        <ErrorModule :module-data="moduleData" v-if="is_error"/>
       
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
