<script lang="ts" setup>
import SMSForm from '../view/smsForm.vue';
    import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
    const breadcrumbsData = ref({
        page_name: 'sms_template',
        name: 'sms_template',
    });

</script>
<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>
        <VCardText>
            <VRow>
               <SMSForm></SMSForm>
            </VRow>
        </VCardText>
        </VCard>
    </section>
</template>