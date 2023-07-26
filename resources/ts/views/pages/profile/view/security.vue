<script lang="ts" setup>


const route = useRoute();
const isCurrentPasswordVisible = ref(false)
const isNewPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)
const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')

const Twosteps = ref(true)
const userData = JSON.parse(localStorage.getItem('userData') || 'null')

const passwordRequirements = [
  'Minimum 8 characters long - the more, the better',
  'At least one lowercase character',
  'At least one number, symbol, or whitespace character',
]


const country = ['India', 'Bangladesh', 'Sri Lanka', 'USA']
    const State = ['Delhi', 'UP', 'Haryana', 'Bihar']
    const City = ['Gurgaon', 'Noida', 'Delhi']
    const Timezone = ['India Time Zone', 'Bangladesh Standard Time']
    const Dateformat = ['DD/MM/YYYY', 'YYYY/MM/DD']
    
    const add_all_checked = ref(false);
    const view_all_checked = ref(false);
    const edit_all_checked = ref(false);
    const delete_all_checked = ref(false);

    const add_all_checked2 = ref(false);
    const view_all_checked2 = ref(false);
    const edit_all_checked2 = ref(false);
    const delete_all_checked2 = ref(false);

    const userId = Number(route.params.id) ?? 0;

    const user_details = JSON.parse(localStorage.getItem('userData') || 'null')
    import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
    import { VForm } from 'vuetify/components';
    import {lengthValidator,requiredValidator,confirmedValidator} from '@validators'
    const isSuccessDialogVisible = ref(false)
    const isErrorDialogVisible = ref(false)
    const successMessage = ref('')
    const errorMessage = ref('')
    const isSnackbarVisible = ref(false);
    const snackbarMessage = ref('');
    const class_name = ref('');
    const refVForm = ref<VForm>();
    const userProfileStore = useUserProfileStore()
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

</script>

<template>
  <VCardText class="pt-0">
  <VRow>
    <VCol cols="12">

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

      <VCard border >
        <VCardText>
          <VRow>
            <VCol cols="12">
              <div class="mb-0 d-flex align-center">
              <h6 class="text-base font-weight-semibold mr-3">Two-steps verification
                <VChip
                  size="small"
                  class="text-capitalize"
                  :color="userData?.is_two_factor_enable == 1 ? 'success' : 'error'"
                >
                <VIcon size="18" :icon="userData?.is_two_factor_enable == 1 ? 'tabler-circle-check' : 'tabler-circle-x'" />
                  {{userData?.is_two_factor_enable == 1 ? $t("active") : $t("inactive")}}
            </VChip>
              </h6>
                
              </div>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
    
    <VCol cols="12">
      <VBtn
        prepend-icon="tabler-edit"
        :to="{ name: 'edit-profile-tab-uid',
        params: {tab:'security', uid: userId} }"
        >
        {{$t('edit_security')}}
      </VBtn>
    </VCol>
  </VRow>
  </VCardText>

</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 5px;
}

.server-close-btn {
  inset-inline-end: 0.5rem;
}
</style>