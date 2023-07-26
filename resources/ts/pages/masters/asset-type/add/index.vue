<script lang="ts" setup>

import breadcrumbs from '@/pages/breadcrumbs/list/index.vue'
import AssetEditable from '@/pages/masters/asset-type/view/AssetEditable.vue';

// set Params
const assetData = ref({
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
            <AssetEditable :asset-data="assetData" :label-text="$t('add')" :snack-bar="$t('add_successfully')">
            </AssetEditable>
        </VCard>
    </div>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: is_add
        subject: platform
</route>