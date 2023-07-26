<script lang="ts" setup>
      import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
import type {permissionParams} from './../types';
import type { VForm } from 'vuetify/components';
import router from '@/router'
    // 👉 Store
const userProfileStore = useUserProfileStore()
const refForm = ref<VForm>();
const route = useRoute()
// const permissions =  ref<permissionParams>();
const userId = Number(route.params.uid) ?? 0;
const permission:any = ref([]);
const isSuccessDialogVisible = ref(false)
    const successMessage = ref('')

    const isSnackbarVisible = ref(false);
    const snackbarMessage = ref('');
    const class_name = ref('');

userProfileStore.getPermissions(userId).then(response => {
    console.log(' response.data', response.data.data);
    permission.value = response.data.data
})
const isFormValid = ref(false)
const onSubmit = () => {
        if(userId) {
            console.log('userData.user_profile',permission.value);
            let req_data = {
                permission:permission.value,
                id:userId
            }
            userProfileStore.updatePermission(req_data)
            .then((response:any) =>{
                // isSuccessDialogVisible.value = true;
                successMessage.value = response.data.message;
                isSnackbarVisible.value = true;
                snackbarMessage.value =successMessage.value;
                class_name.value = 'success-snackbar';
                router.push({
                    name:"edit-profile-tab-uid", params: {tab:'advance-setting', uid: userId}
                })
            })
           
          .catch((e) => {
           
          })
        }
       
    }

</script>

<template>
    <VCardText class="pt-0">
        <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
            class="form-border"
        >

            <VRow>
                <VCol cols="12">
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <h5>
                                        <VIcon
                                            :size="16"
                                            icon="tabler-user"
                                        />                         
                                        {{$t("permissions")}}
                                    </h5>
                                </VCol>

                                <VCol cols="12">

                                    <VExpansionPanels class="permission_expend">
                                        <VExpansionPanel v-for="module,index in permission" :key="index">
                                            <VExpansionPanelTitle v-for="(value, key) in module.privileges">
                                                <b>{{key }}</b>
                                            </VExpansionPanelTitle>
                                            <VExpansionPanelText>
                                                <VTable>
                                                    <thead>
                                                        <tr v-for="(value, key) in module.privileges">
                                                            <th><b>{{key }}</b></th>
                                                            <th>
                                                                <VCheckbox
                                                                    class="checkbox-label-font add-check"
                                                                    :label="$t('add')"
                                                                />
                                                            </th>
                                                            <th>
                                                                <VCheckbox
                                                                    class="checkbox-label-font add-check"
                                                                    :label="$t('view')"
                                                                />
                                                            </th>
                                                            <th>
                                                                <VCheckbox
                                                                    class="checkbox-label-font add-check"
                                                                    :label="$t('edit')"
                                                                />
                                                            </th>
                                                            <th>
                                                                <VCheckbox
                                                                    class="checkbox-label-font add-check"
                                                                    :label="$t('add')"
                                                                />
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-for="sub_module in module.sub_modules"  :key="sub_module.id">
                                                    
                                                    <tr  v-for="(value, key) in sub_module.privileges">
                                                        <td >{{key}}</td>
                                                        <td class="text-center">
                                                            <VCheckbox
                                                                class="checkbox-label-font add-check"
                                                                v-model="sub_module.privileges[key].is_add"
                                                                :label="$t('add')"  
                                                                true-value="1"
                                                            false-value="0" 
                                                            
                                                            />
                                                        </td>
                                                        <td class="text-center">
                                                            <VCheckbox
                                                                class="checkbox-label-font"
                                                                v-model="sub_module.privileges[key].is_view"
                                                                :label="$t('view')" 
                                                                true-value="1"
                                                            false-value="0" 
                                                            />
                                                        </td>
                                                        <td class="text-center">
                                                            <VCheckbox
                                                                class="checkbox-label-font"
                                                                v-model="sub_module.privileges[key].is_edit"
                                                                :label="$t('edit')" 
                                                                true-value="1"
                                                            false-value="0" 
                                                            />
                                                        </td>
                                                        <td class="text-center">
                                                            <VCheckbox
                                                                class="checkbox-label-font"
                                                                v-model="sub_module.privileges[key].is_delete"
                                                                :label="$t('delete')" 
                                                                true-value="1"
                                                            false-value="0" 
                                                            />
                                                        </td>
                                                    </tr>
                                                        
                                                </tbody>
                                                </VTable>
                                            </VExpansionPanelText>
                                        </VExpansionPanel>
                                    </VExpansionPanels>
                                </VCol>

                                <VCol cols="12" class="text-right">
                                    <VBtn
                                        variant="outlined"
                                        color="secondary mr-3"
                                        :to="{
                                                name: 'profile-tab-id',
                                                params: {tab:'permissions', id: userId}
                                            }"
                                    >
                                        {{$t("can_btn")}}
                                    </VBtn>
                                    <VBtn
                                        class="mr-3" 
                                        color="primary"
                                        :to="{name:'edit-profile-tab-uid', params: {tab:'security', uid: userId} }"

                                    >
                                        {{$t("previous")}}
                                    </VBtn>
                                    <VBtn
                                        type="submit"
                                        color="primary"

                                    >
                                        {{$t("next")}}
                                    </VBtn>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>    
            </VRow>
        </VForm>
  </VCardText>

  <SuccessDialog
            v-model:isDialogVisible="isSuccessDialogVisible"
            v-model:success-msg="successMessage"
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