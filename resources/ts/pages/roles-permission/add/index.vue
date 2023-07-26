<script setup lang="ts">
import * as demoCode from '@/views/demos/forms/form-validation/demoCodeFormValidation';
import RolesEditable from '../view/RolesEditable.vue'
import type {RoleParams} from "./../view/type";
import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'



const moduleData = ref<RoleParams>({
    title: '',
    slug:'',
    created_by:'',
    updated_at:''
})
    const breadcrumbsData = ref({
        page_name: 'add_role_and_permission',
        name: 'add_role_and_permission',
    });

</script>

<template>
    <section>
    <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>
            
            <VCardText>
                <VRow>
                    <VCol cols="12" md="12">
                        <RolesEditable :module-data="moduleData"/>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>