<script setup lang="ts">
import type { VForm } from 'vuetify/components'
import { emailValidator, requiredValidator } from '@validators';

import router from '@/router'
import { useRoute } from 'vue-router';
import { useModuleStore } from './../view/modulesStore';

import type {ModulesParams,RoleParams,ParentParams} from '../view/types'

const refForm = ref<VForm>();

const status = [{title:'Active',value:1}, {title:'Inactive',value:0}];

const isFormValid = ref(false)
const message = ref('')

const moduleStore = useModuleStore()
const route = useRoute();
const isSnackbarVisible = ref(false);
    const snackbarMessage = ref('');
    const class_name = ref('');

interface Props {
  moduleData:ModulesParams,
}

const props  = defineProps<Props>();
console.log('props',props);

const errors = ref<Record<string, string | undefined>>({
  title: undefined,
  slug: undefined,
  is_parent: undefined,
})


const onSubmit = () => {
  let moduleId = Number(route.params.id) ?? 0;
        if(moduleId) {
          // props.moduleData.role = JSON.stringify(props.moduleData.role);
          moduleStore.updateModules(props.moduleData)
          .then((response:any) =>{
            // props.moduleData.role = JSON.parse(props.moduleData.role);
            message.value =response.data.message;
            isSnackbarVisible.value = true;
                    snackbarMessage.value =message.value;
                    class_name.value = 'success-snackbar';
              router.push({
                name:"module-management-list"
              })
          })
          .catch((e) => {
            if(e.response.data.message) {
              message.value =e.response.data.message;
            }
            const {errors:formErrors} = e.response.data;
            errors.value = formErrors;
          })
        }
        else {
          // props.moduleData.role = JSON.stringify(props.moduleData.role);
          moduleStore.addModules(props.moduleData)
          .then((response:any) =>{
            message.value =response.data.message;
            isSnackbarVisible.value = true;
                    snackbarMessage.value =message.value;
                    class_name.value = 'success-snackbar';
              router.push({
                name:"module-management-list"
              })
           
          })
          .catch((e) => {
            if(e.response.data.message) {
              message.value =e.response.data.message;
           
            }
            const {errors:formErrors} = e.response.data;
            errors.value = formErrors;
          })
        }
}
// role list fetch
const roleList = ref();
let child_id = ref();
const roleFetch = () => {
  moduleStore.fetchRolesList()
      .then((response:any) =>{
          if(response.data.success) {
            roleList.value = response.data.data;
          }
        })
        .catch((e) => {
        
        });
}

// role list fetch
const moduleList = ref<[]>([]);
const moduleFetch = () => {
  moduleStore.fetchParentList()
          .then((response:any) =>{
           if(response.data.success) {
            moduleList.value = response.data.data;
           }
          })
          .catch((e) => {
           
          })
}

const childList = ref<[]>([]);
const getParentId = (id:any) => {
  if(!id) return;
  
  moduleStore.fetchChildList(id)
          .then((response:any) =>{
            let child_data_list = response.data.data;
            console.log(child_data_list);
           if(response.data.success) {
            console.log('child_data_list',child_data_list);
            if(response.data && !child_data_list.length) {
              props.moduleData.child_id = '';
            }
            childList.value = response.data.data;
            }
          })
          .catch((e) => {
           
          })
}


watchEffect(() => {
  moduleFetch(); 
  roleFetch(); 
  let moduleId = Number(route.params.id) ?? 0;
  setTimeout(() => {
    let parent_id = props.moduleData.parent_id ?? 0;
    props.moduleData.is_parent = 0;

    // get parent if this is child
    if(parent_id) {
      getParentId(parent_id);
      props.moduleData.is_parent = 1;
      props.moduleData.child_id = moduleId;
      child_id.value = {
        id:moduleId,
        title:props.moduleData.title
      }
    }
    if(props.moduleData.role && props.moduleData.role.length) {
      let roles = props.moduleData.role;
      let selected_role:any = [];
      roles.forEach((element:any) => {
        selected_role.push(element.id);
        
      });
      props.moduleData.role = selected_role;
      
    }
    const is_active = props.moduleData.status;
    selectStatus(is_active);
    
  }, 2000);
        
})

const selectStatus = (is_active:any) => {
  
    props.moduleData.status = is_active ? 1 : 0;

}

</script>

<template >
  
  
  <h5 class="mb-4">{{$t("add_btn")}} {{$t("module_management")}}</h5>
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
			:label="$t('title')"
			:rules="[requiredValidator]"
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
                <label>{{$t('parent_module')}}</label>
                </VCol>
                <VCol cols="12"
					md="8">
                <VRadioGroup
                v-model="props.moduleData.is_parent"
                inline
                >
                    <VRadio
                    :label="$t('parent_module')"
                        :value="0"
                    />
                    <VRadio
                        :label="$t('child_module')"
                        :value="1"
                    />
                </VRadioGroup>
                </VCol>
            </VRow>
            </div>
      	</VCol>
		
        <VCol
            cols="12"
            md="6"
        >
            <VAutocomplete
              v-model="props.moduleData.parent_id"
              :items="moduleList"
              item-value="id"
              :label="$t('select_parent')"
              @blur="getParentId(props.moduleData.parent_id ?? 0);props.moduleData.child_id = ''"
              :error-messages="errors.parent_id"
              :menu-props="{ maxHeight: '300' }"
              clearable
          />
        </VCol>
        <VCol
            cols="12"
            md="6"
            v-if="props.moduleData.is_parent == 1" 
        >
            <VSelect
            v-model="child_id"
            item-value="id"
            :items="childList"
            :label="$t('select_child')"
        />
        </VCol>

		<VCol
			cols="12"
			md="6"
		>
			<VTextField
      :rules="[requiredValidator]"
      v-model="props.moduleData.icon"
      :label="$t('icon')"
			/>
      	</VCol>

		<VCol
            cols="12"
            md="6"
        >
		<VAutocomplete
    v-model="props.moduleData.role"
            :items="roleList"
            :rules="[requiredValidator]"
            item-value="id"
            :menu-props="{ maxHeight: '400' }"
            :label="$t('roles')"
            multiple
            clearable
        >
            <template v-slot:selection="{ item, index }">
                <v-chip v-if="index < 2">
                <span>{{ item.title }}</span>
                </v-chip>
                <span
                    v-if="index === 2"
                    class="text-grey text-caption align-self-center"
                >
                    (+{{roleList.length - 2 }} others)
                </span>
            </template>
	    </VAutocomplete>
    </VCol>

    <VCol
			cols="12"
			md="6"
		>
			<VSelect
      v-model="props.moduleData.status"
      :rules="[requiredValidator]"
				:items="status"
        :label="$t('status')"
			/>
      	</VCol>

      

      <VCol cols="12" class="text-right">
        <VBtn
        :to="{ name: 'module-management-list'}"
            color="secondary mr-3"
        >
          {{$t("can_btn")}}
        </VBtn>
        <VBtn
          type="submit"
          color="primary"
        >
          {{$t("sub_btn")}}
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