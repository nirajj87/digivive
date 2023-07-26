<script setup lang="ts">

import ModuleForm from '../view/moduleForm.vue';
import { useModuleStore } from './../view/modulesStore';
import { useRoute } from 'vue-router';
import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
const moduleStore = useModuleStore()
const route = useRoute();


const moduleData:any = ref({
    title: '',
    slug:'',
    status:'',
    parent_id:'',
    child_id:'',
    is_parent:0,
    order:'',
    created_by:'',
    updated_at:'',
    created_at:'',
    // role:''
});
watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if(moduleId) {
        moduleStore.fetchModulesDetails(moduleId)
          .then((response:any) =>{
            console.log('edit response',response.data);
          
          
           
            if(response.data.success) {
                moduleData.value = response.data.data;
            // moduleData.value.is_parent = '1';
           }
          })
          .catch((e) => {
           
          })
        }
})
const breadcrumbsData = ref({
        page_name: 'edit_module',
        name: 'edit_module',
    });



</script>

<template>
    <section>
   <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
    <VCard>
        <VCardText>
            <VRow>
                <VCol cols="12" md="12">
                    <ModuleForm  :module-data="moduleData" />
                </VCol>
            </VRow>
        </VCardText>
    </VCard>
    </section>
</template>