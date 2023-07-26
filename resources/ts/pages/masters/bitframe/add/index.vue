<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import BitframeEditable from '@/pages/masters/bitframe/view/BitframeEditable.vue';

// set Params
const bitframeData = ref({
    type: ''
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
            <BitframeEditable :bitframe-data="bitframeData" :label-text="$t('add')" :snack-bar="$t('add_successfully')">
            </BitframeEditable>
        </VCol>
    </VCard>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: is_add
        subject: bitframe
</route>