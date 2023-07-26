<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRouter, useRoute } from 'vue-router';
import { useAnalyticsStore } from '../view/AnalyticsStore';
import { AnalyticsModel } from '../view/type'

const AnalyticsStore = useAnalyticsStore()
const route = useRoute();

const analyticsData = ref<AnalyticsModel>({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'analytics_management',
    name: 'analytics_management',
});

onBeforeMount(() => {

    let analyticsId = Number(route.params.id) ?? 0;


    AnalyticsStore.getDetailsAnalytics(analyticsId)
        .then((response: any) => {
            analyticsData.value = response.data;
        })
        .catch((error) => {
            console.log("Some Internal Server Error Occured");

        })

})


</script>


<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>

            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("platform") }}</h5>
                        <VCard border>
                            <VCardText>
                                <VRow>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("analytics_name") }}</p>
                                        <h5>{{ analyticsData.title }}</h5>
                                    </VCol>
                                    
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <h5> {{ analyticsData.status == '1' ? 'Active' : (analyticsData.status == '0' ?
                                            'Inactive' : (analyticsData.status == '2' ? 'Blocked' : 'Status Does not exist'))
                                        }} </h5>

                                    </VCol>
                                </VRow>

                                <VRow class="mt-2">
                                    <VCol cols="12" class="text-right">
                                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-analytics-management' }">
                                            {{ $t("back") }}
                                        </VBtn>
                                    </VCol>
                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section></template>
