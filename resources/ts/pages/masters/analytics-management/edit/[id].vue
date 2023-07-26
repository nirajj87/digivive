<script setup lang="ts">
import { useAnalyticsStore } from '../view/AnalyticsStore';
import { useRoute, useRouter } from 'vue-router';
import { AnalyticsModel } from '../view/type'
import AnalyticsEditable from '../view/AnalyticsEditable.vue';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const AnalyticsStore = useAnalyticsStore()
const route = useRoute();
const router = useRouter();

const analyticsData = ref<AnalyticsModel>({
    title: ''
})

watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (moduleId) {
        AnalyticsStore.getDetailsAnalytics(moduleId)
            .then((response: any) => {
                if (response) {
                    analyticsData.value = response.data;
                }
            })
            .catch((e) => {
                console.log("Some internal server error occured");
                router.push({ name: 'masters-analytics-management' })

            })
    }
})


const breadcrumbsData = ref({
    page_name: 'edit_analytics',
    name: 'edit_analytics',
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
                        <AnalyticsEditable :analytics-data="analyticsData" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>