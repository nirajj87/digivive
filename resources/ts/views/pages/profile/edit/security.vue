<script lang="ts" setup>

import { VForm } from 'vuetify/components';
import {lengthValidator,requiredValidator,confirmedValidator} from '@validators'
import type {ChangePassword} from './../types'
import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
    const isCurrentPasswordVisible = ref(false)
    const isNewPasswordVisible = ref(false)
    const isConfirmPasswordVisible = ref(false)
    const currentPassword = ref('')
    const newPassword = ref('')
    const confirmPassword = ref('')
    const isSuccessDialogVisible = ref(false)
    const isErrorDialogVisible = ref(false)
    const successMessage = ref('')
    const errorMessage = ref('')
    const email = ref('')
    const user_details = JSON.parse(localStorage.getItem('userData') || 'null')
    const userData:any =   ref({
        email:user_details.email,
        current_password:'',
        new_password:'',
        conform_pass:''

    })
   
    const route = useRoute()
    const Twosteps = ref(user_details.is_two_factor_enable ? true : false)
    const is_otp_send = ref(false)
    const userId = Number(route.params.uid) ?? 0;
    const refVForm = ref<VForm>();
    const userProfileStore = useUserProfileStore()
    const interval = ref()
    const otp_expire_time = ref(0)
    const otp = ref('');

    const isSnackbarVisible = ref(false);
    const snackbarMessage = ref('');
    const class_name = ref('');

    const errors = ref<Record<string, string | undefined>>({
                    current_password: undefined,
                    new_password: undefined,
                    conform_pass: undefined,
                })
     const changePassword = () => {
        userProfileStore.changePassword(userData.value)
          .then((response:any) =>{
            resetForm();
            // isSuccessDialogVisible.value = true;
            successMessage.value = response.data.message;
            isSnackbarVisible.value = true;
                    snackbarMessage.value =successMessage.value;
                    class_name.value = 'success-snackbar';
          })
           
          .catch((e) => {
            const { errors: formErrors } = e.response.data
             // errors.value = formErrors
            let res = e.response.data.data;
            
            console.log(res);
            
            for (const key in res) {
                for (const e_key in errors.value) {
                if(key == e_key) {
                    errors.value[e_key] = res[key][0];
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
      changePassword();
      
    })
}
const resetForm = () => {
        for (const e_key in errors.value) {
            errors.value[e_key] = '';
        }
         for (const form_key in userData.value) {
            userData.value[form_key] = null;
        }
        userData.value.email = user_details.email;
}

const enableDisable2FA = () => {
    const data = {
        email: user_details.email
    }
    userProfileStore.enableDisable2FA(data)
          .then((response:any) =>{
            is_otp_send.value = true;
            // isSuccessDialogVisible.value = true;
            successMessage.value = response.data.message;
            isSnackbarVisible.value = true;
                    snackbarMessage.value =successMessage.value;
                    class_name.value = 'success-snackbar';
          })
          .catch((e) => {
            let res = e.response.data;
            showErrorModal(res);
            const { errors: formErrors } = e.response.data
             // errors.value = formErrors
           
           
          })
}

const verifyOTP = () => {
    const data = {
        email: user_details.email,
        otp:otp.value
    }
    userProfileStore.twoFactorAuthenticate(data)
          .then((response:any) =>{
            // isSuccessDialogVisible.value = true;
            successMessage.value = response.data.message;
            isSnackbarVisible.value = true;
                    snackbarMessage.value =successMessage.value;
                    class_name.value = 'success-snackbar';
            is_otp_send.value = false;
            user_details.is_two_factor_enable = user_details.is_two_factor_enable ? 0 : 1;
            localStorage.setItem('userData',JSON.stringify(user_details))
          })
          .catch((e) => {
            let res = e.response.data;
            showErrorModal(res);
            // errorMessage.value = e.response.data.data.otp[0];
           
           
          })
}
const resendOTP = () => {
    const data = {
        email: user_details.email
    }
    userProfileStore.resendTwoFactorAuthCode(data)
          .then((response:any) =>{
            // isSuccessDialogVisible.value = true;
            successMessage.value = response.data.message;
            isSnackbarVisible.value = true;
                    snackbarMessage.value =successMessage.value;
                    class_name.value = 'success-snackbar';
            otp_expire_time.value = 30;
            clearInterval(interval.value);
            interval.value = setInterval(() => {
                otp_expire_time.value = otp_expire_time.value-1
            }, 1000)

          })
          .catch((e) => {
            let res = e.response.data;
            showErrorModal(res);
          })
}

const showErrorModal = (res:any) => {
    // isErrorDialogVisible.value = true;
    for (const key in res.data) {
        errorMessage.value = res.data[key][0];
        isSnackbarVisible.value = true;
        snackbarMessage.value =errorMessage.value;
        class_name.value = 'delete-snackbar';
        break;
    }
}

const passwordRequirements = [
  'Minimum 8 characters long - the more, the better',
  'At least one lowercase character',
  'At least one number, symbol, or whitespace character',
]


const getOtp = (value: string) => {
    otp.value = value;
 console.log(otp);
 
}

// onMounted(() => {
//   interval.value = setInterval(() => {
//     otp_expire_time.value = otp_expire_time.value-1
//   }, 1000)
// })






</script>

<template>
    <VCardText class="pt-0">
        <VForm
            ref="refVForm"
            @submit.prevent="onSubmit"
          >
        <VRow>
           
            <!-- SECTION: Change Password -->
            <VCol cols="12" >
            <VCard border >
                <VCardText >
                    <h5 class="mb-2">{{ $t("change_password") }}</h5>
                    <!-- 👉 Current Password -->
                    <VRow class="mb-3">
                    <VCol
                        cols="12"
                        md="6"
                    >
                        <!-- 👉 current password -->
                        <VTextField
                        v-model="userData.current_password"
                        :rules="[requiredValidator]"
                        :type="isCurrentPasswordVisible ? 'text' : 'password'"
                        :append-inner-icon="isCurrentPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                        :label="$t('enter_old_password')"
                        @click:append-inner="isCurrentPasswordVisible = !isCurrentPasswordVisible"
                        :error-messages="errors.current_password"
                        />
                    </VCol>
                    </VRow>

                    <!-- 👉 New Password -->
                    <VRow>
                    <VCol
                        cols="12"
                        md="6"
                    >
                        <!-- 👉 new password -->
                        <VTextField
                        v-model="userData.new_password"
                        :type="isNewPasswordVisible ? 'text' : 'password'"
                        :rules="[requiredValidator]"
                        
                        :append-inner-icon="isNewPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                        :label="$t('enter_new_password')"
                        @click:append-inner="isNewPasswordVisible = !isNewPasswordVisible"
                        :error-messages="errors.new_password"
                        />
                    </VCol>

                    <VCol
                        cols="12"
                        md="6"
                    >
                        <!-- 👉 confirm password -->
                        <VTextField
                        v-model="userData.conform_pass"
                        :type="isConfirmPasswordVisible ? 'text' : 'password'"
                        :rules="[requiredValidator,confirmedValidator(userData.conform_pass, userData.new_password)]"
                        :append-inner-icon="isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                        :label="$t('enter_confirm_password')"
                        @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                        :error-messages="errors.conform_pass"
                        />
                    </VCol>
                    </VRow>
                </VCardText>

                <!-- 👉 Password Requirements -->
                <VCardText>
                    <h6 class="text-base font-weight-medium mb-3">{{$t("password_requirements")}}:</h6>

                    <VList class="card-list">
                    <VListItem
                        v-for="item in passwordRequirements"
                        :title="item"
                    >
                        <template #prepend>
                        <VIcon
                            size="8"
                            icon="tabler-circle"
                            class="me-3"
                        />
                        </template>
                    </VListItem>
                    </VList>
                </VCardText>

                <VCardText class="text-right">
                    <VBtn
                        variant="outlined"
                        color="secondary mr-3"
                    >
                    {{$t("can_btn")}}
                    </VBtn>
                    <VBtn
                        type="submit"
                        color="primary"
                    >
                        {{$t("update_password")}}
                    </VBtn>
            
                </VCardText>
                </VCard>
            </VCol>
            </VRow>
            </VForm>
            <!-- SECTION Two-steps verification -->
            <VRow>
            <VCol cols="12">
                <VCard border >
                    <VCardText>
                        <VRow>
                            <VCol cols="12">
                                <div class="mb-0 d-flex align-center">
                                <h6 class="text-base font-weight-semibold mr-3">Two-steps verification</h6>
                                    <VSwitch title="Two-steps verification" v-model="Twosteps" inset @change="!Twosteps;enableDisable2FA()" />   
                                </div>
                                <VRow v-if="is_otp_send">
                                    <VCol cols="12" md="5">
                                        <AppOtpInput class="otp_input"  @update-otp="getOtp"  />
                                        <small>
                                            <span class="me-1 f-left">{{ $t("otp_info_sms") }}</span>
                                            <!-- <a @click="otp_expire_time = 30" v-if="otp_expire_time <1" class="f-right" href="javascript:void(0);">{{ $t("resend_otp") }} </a> -->
                                            <span class="f-left" v-if="otp_expire_time > 0">{{otp_expire_time <10 ? ('0'+ otp_expire_time) : otp_expire_time }}{{$t("sec")}}</span>
                                            <a @click="resendOTP()"  class="f-right" href="javascript:void(0);">{{ $t("resend_otp") }} </a>
                                        </small>
                                    </VCol>
                                    <VCol cols="12" class="text-right">
                                        <VBtn
                                        variant="outlined"
                                        color="secondary mr-3"
                                        >
                                            {{$t("can_btn")}}
                                        </VBtn>
                                        <VBtn
                                            type="submit"
                                            color="primary"
                                            @click="verifyOTP();"
                                            
                                        >
                                            {{$t("sub_btn")}}
                                        </VBtn>
                                    </VCol>
                                </VRow>
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>

        <VRow>
            <VCol cols="12 text-right">
                <VBtn
                    variant="outlined"
                    color="secondary mr-3"
                    :to="{ name: 'profile-tab-id',
                       params: {tab:'security', id: userId} }"
                >
          {{$t("can_btn")}}
        </VBtn>
        <VBtn    class="mr-3"                    
                color="primary"
                :to="{name:'edit-profile-tab-uid', params: {tab:'billing', uid: userId} }"
            >
                {{$t("previous")}}
        </VBtn>
        <VBtn
          type="submit"
          color="primary"
          :to="{ name: 'edit-profile-tab-uid',
                       params: {tab:'permissions', uid: userId} }"
        >
          {{$t("next")}}
        </VBtn>
            </VCol>
        </VRow>
    </VCardText>

     <!-- 👉 succes and error Dialog -->
     <SuccessDialog
            v-model:isDialogVisible="isSuccessDialogVisible"
            v-model:success-msg="successMessage"
          />
          <ErrorDialog
            v-model:isDialogVisible="isErrorDialogVisible"
            v-model:error-msg="errorMessage"
          />

             <!-- SnackBar -->
  <VSnackbar v-model="isSnackbarVisible" location="top end" :class="class_name">
            {{$t(snackbarMessage) }}
        </VSnackbar>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 5px;
}

.server-close-btn {
  inset-inline-end: 0.5rem;
}

</style>