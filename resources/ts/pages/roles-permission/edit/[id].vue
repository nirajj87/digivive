<script setup lang="ts">
import * as demoCode from '@/views/demos/forms/form-validation/demoCodeFormValidation';
import RolesEditable from '../view/RolesEditable.vue'
import { useRoleModuleStore } from './../view/roleModulesStore';
import { useRoute } from 'vue-router';
import type {RoleParams} from "./../view/type";
import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
const roleListStore = useRoleModuleStore()
const route = useRoute();


const moduleDatae = ref({
    title: '',
    slug:'',
    is_parent:'',
    created_by:'',
    updated_at:''
})
watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if(moduleId) {
        roleListStore.fetchModulesDetails(moduleId)
          .then((response:any) =>{
            console.log(response.data);
           if(response.data.success) {
          
            moduleDatae.value = response.data.data
            // moduleData.value.is_parent = '1';
           }
          })
          .catch((e) => {
           
          })
        }
})

const breadcrumbsData = ref({
        page_name: 'edit_role_and_permission',
        name: 'edit_role_and_permission',
    });

</script>

<template>
 <section>
    <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <VCard>
        
            <VCardText>
                <VRow>
                    <VCol cols="12" md="12">
                        <RolesEditable :module-data="moduleDatae" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>