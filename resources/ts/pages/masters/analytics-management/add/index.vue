<script setup lang="ts">
import AnalyticsEditable from '../view/AnalyticsEditable.vue'
import { AnalyticsModel } from '../view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';


const analyticsParams = ref<AnalyticsModel>({
    title: '',
})


const breadcrumbsData = ref({
    page_name: 'add_analytics',
    name: 'add_analytics',
});

</script>

<template>
    <div>
        <!-- 👉 BreadCrumbs -->
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <VCard>
            <VCardText>
                <VRow>
                    <VCol cols="12" md="12">
                        <AnalyticsEditable :analytics-data="analyticsParams" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>