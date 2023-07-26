<script setup lang="ts">
import * as demoCode from '@/views/demos/forms/form-validation/demoCodeFormValidation';
import ModuleForm from '../view/moduleForm.vue';
import { useModuleStore } from './../view/modulesStore';
import { useRoute } from 'vue-router';
import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
const moduleStore = useModuleStore()
const route = useRoute();

const moduleData = ref({
    title: '',
    slug:'',
    parent_id:'',
    child_id:'',
    is_parent:0,
    icon:'',
    active_icon:'',
    created_by:'',
    updated_at:'',
    created_at:'',
    // role:''
});


const breadcrumbsData = ref({
        page_name: 'add_module',
        name: 'add_module',
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