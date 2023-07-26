<script lang="ts" setup>

import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import ContentEditable from '@/pages/masters/content-availability/view/ContentEditable.vue';

// set content Params
const contentData = ref({
    title: ''
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
            <ContentEditable :content-data="contentData" :label-text="$t('add')" :snack-bar="$t('add_successfully')">
            </ContentEditable>
        </VCard>
    </div>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: is_add
        subject: platform
</route>