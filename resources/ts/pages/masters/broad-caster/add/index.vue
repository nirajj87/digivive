<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import BroadCasterEditable from '@/pages/masters/broad-caster/view/BroadCasterEditable.vue';

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
    <div>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <VCard>
            <VCol cols="12" md="12">
                <BroadCasterEditable :platform-data="platformData" :label-text="$t('add')"
                    :snack-bar="$t('add_successfully')">
                </BroadCasterEditable>
            </VCol>
        </VCard>
    </div>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: is_add
        subject: platform
</route>