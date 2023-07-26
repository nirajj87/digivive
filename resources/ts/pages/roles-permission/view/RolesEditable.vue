<script lang="ts" setup>
// eslint-disable-next-line @typescript-eslint/consistent-type-imports
import type { VForm } from 'vuetify/components'
import { emailValidator, requiredValidator } from '@validators'
import router from '@/router'
import { useRoute } from 'vue-router';
import { useRoleModuleStore } from './../view/roleModulesStore';

import type {RoleParams} from '../view/type'



const message = ref('')
const status = ref('')
const isFormValid = ref(false)
const isSnackbarVisibileDialog = ref(false)
const route = useRoute();
const roleStore = useRoleModuleStore();
const isSnackbarVisible = ref(false);
    const snackbarMessage = ref('');
    const class_name = ref('');

// Form Errors
const errors = ref<Record<string, string | undefined>>({
  title: undefined,
  slug: undefined,
  is_parent: undefined,
})
console.log('error====',errors);


interface Props {
  moduleData:RoleParams
}
console.log('Interface Props ===');

const props  = defineProps<Props>();
console.log('props===',props) ;

const refForm = ref<VForm>();
console.log('refForm====',refForm);



  const onSubmit = () => {
   
    // refForm.value?.validate.then(({valid}) =>{
    //   if(valid) {
        let moduleId = Number(route.params.id) ?? 0;
        if(moduleId) {
          roleStore.updateModules(props.moduleData)
          .then((response:any) =>{
            message.value =response.data.message;
            isSnackbarVisible.value = true;
                    snackbarMessage.value =message.value;
                    class_name.value = 'success-snackbar';
            setTimeout(() => {
              router.push({
                name:"roles-permission-list"
              })
            }, 2000);
          })
          .catch((e) => {
            if(e.response.data.message) {
              message.value =e.response.data.message;
            status.value = "failed";
            isSnackbarVisibileDialog.value = true;
            }
            const {errors:formErrors} = e.response.data;
            errors.value = formErrors;
          })
        }
        else {
          roleStore.addModules(props.moduleData)
          .then((response:any) =>{
            message.value =response.data.message;
            isSnackbarVisible.value = true;
                    snackbarMessage.value =message.value;
                    class_name.value = 'success-snackbar';
            setTimeout(() => {
              router.push({
                name:"roles-permission-list"
              })
            }, 2000);
          })
          .catch((e) => {
            if(e.response.data.message) {
              message.value =e.response.data.message;
            status.value = "failed";
            isSnackbarVisibileDialog.value = true;
            }
            const {errors:formErrors} = e.response.data;
            errors.value = formErrors;
          })
        }
      }
  //   })
  // };
</script>

<template >
  
  
  <h5 class="mb-4">{{$t("add_btn")}} {{$t("role_and_permission")}}</h5>
  <!-- <Toast v-model:isSnackbarVisibility = "isSnackbarVisibileDialog" 
  :message="message"
  :status="status"
  /> -->
  <VForm
    ref="refForm"
    v-model="isFormValid"
    @submit.prevent="onSubmit"
    class="form-border"
  >
    
    <VRow>
      
      <VCol
        cols="12"
        md="6"
      >
        <VTextField
          v-model="props.moduleData.title"
          :label="$t('Modules.Title')  + '*'"
          :rules="[requiredValidator]"
          :error-messages="errors.title"
        />
      </VCol>
	  <VCol
			cols="12"
			md="6"
		>
		<div class="box-border">
            <VRow no-gutters>
                
                
                <VCol cols="12"
					md="4">
                <label>{{$t('parent_module')}} *</label>
                </VCol>
                <VCol cols="12"
					md="8">
                <VRadioGroup
                v-model="props.moduleData.is_parent"
                inline
                >
                    <VRadio
                    :label="$t('Modules.Yes')"
                        :value="1"
                        :checked="props.moduleData.is_parent == '1'"
                    />
                    <VRadio
                    :label="$t('Modules.No')"
                        :value="0"
                        :checked="props.moduleData.is_parent == '0'"
                    />
                </VRadioGroup>
                </VCol>
				
            </VRow>
		</div>
      	</VCol>

      <VCol cols="12" class="text-right">
		<VBtn
    :to="{ name: 'roles-permission-list'}"
            color="secondary mr-3"
        >
          {{$t("can_btn")}}
        </VBtn>
        <VBtn
          type="submit"
          @click="refForm?.validate()"
        >
          {{$t("add_btn")}}
        </VBtn>
      </VCol>
    </VRow>
  </VForm>

  <!-- SnackBar -->
  <VSnackbar v-model="isSnackbarVisible" location="top end" :class="class_name">
            {{$t(snackbarMessage) }}
        </VSnackbar>

  
</template>
<style lang="scss">
  .form-border{
    border: 1px solid;
    border-color: rgba(var(--v-border-color),var(--v-border-opacity));
    padding: 15px;
    border-radius: 6px;
  }
  .box-border{
    border: 1px solid;
    border-color: #B1B0B5;
    border-radius: 6px;
	padding: 0 16px;
  }
  .box-border label{
	display: flex;
    align-items: center;
    height: 100%;
  }
</style>