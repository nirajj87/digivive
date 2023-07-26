<script lang="ts" setup>


import EmailForm from '../view/emailForm.vue';
   

    import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
    const breadcrumbsData = ref({
        page_name: 'email_template',
        name: 'email_template',
    });

</script>
<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>
            <VCardText>
            <VRow>
              <EmailForm></EmailForm>
            </VRow>
            </VCardText>
        </VCard>
            
        
    </section>
</template>