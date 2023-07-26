<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import DateEditable from '@/pages/masters/date-formats/view/DateEditable.vue';

// set platform Params
const platformData = ref({
    title: '',
    format_key: '',
    order: ''
})

const breadcrumbsData = ref({
    page_name: 'add_client',
    name: 'add_client',
});

</script>

<template>
    <div>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>
            <VCol cols="12" md="12">
                <DateEditable :platform-data="platformData" :label-text="$t('add')" :snack-bar="$t('add_successfully')">
                </DateEditable>
            </VCol>
        </VCard>
    </div>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: is_add
        subject: date-formats
</route>