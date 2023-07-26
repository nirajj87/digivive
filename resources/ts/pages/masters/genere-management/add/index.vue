<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import GenereEditable from '@/pages/masters/genere-management/view/GenereEditable.vue';

// set platform Params
const platformData = ref({
    title: '',
})

const breadcrumbsData = ref({
    page_name: 'add_client',
    name: 'add_client',
});

</script>

<template>
    <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
    <VCard>
        <VCol cols="12" md="12">
            <GenereEditable :platform-data="platformData" :label-text="$t('add')" :snack-bar="$t('add_successfully')">
            </GenereEditable>
        </VCol>
    </VCard>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: is_add
        subject: platform
</route>