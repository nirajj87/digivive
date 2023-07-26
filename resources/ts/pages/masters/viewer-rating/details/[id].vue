<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { ref, watchEffect } from 'vue';
import type { ViewerRatingList } from './../view/types';
import { useViewerRatingStore } from './../view/useViewerRatingStore';
import { useRoute } from 'vue-router';

// 👉 Store
const viewerListStore = useViewerRatingStore()
const viewerList = ref<ViewerRatingList>()

const route = useRoute();
const isLoading = ref(true)

// 👉 Fetching Details
const fetchDetails = () => {
    return new Promise((resolve, reject) => {
        isLoading.value = true
        let platformId = Number(route.params.id) ?? 0;
        if (platformId) {
            viewerListStore.fetchDetails(platformId)
                .then((response: any) => {
                    isLoading.value = false
                    viewerList.value = response.data

                    resolve(viewerList);
                })
                .catch(error => {
                    console.log("Error in Details===>", error);
                    reject(viewerList);
                });
        }
    });
};

watchEffect(fetchDetails);

const breadcrumbsData = ref({
    page_name: 'view',
    name: 'view',
});

</script>

<template>
    <section>
        <!-- 👉 BreadCrumbs -->
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <VCard>
            <VCardText>
                <h5 class="mb-2">{{ $t('profile_details') }}</h5>
                <VCard border>
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("title") }}</p>
                                <h5>{{ viewerList?.title }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("slug") }}</p>
                                <h5>{{ viewerList?.slug }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("order") }}</p>
                                <h5>{{ viewerList?.view_order }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("status") }}</p>
                                <h5>{{ viewerList?.status == '1' ? ($t('active')) : ($t('inactive')) }}</h5>
                            </VCol>
                           
                            <VCol cols="12" class="text-center" v-if="isLoading">
                                <div class="loading-logo">
                                    <PageLoader> </PageLoader>
                                </div>
                                <span v-show="!viewerList && !isLoading">{{ $t('no_data_found') }}</span>
                            </VCol>

                        </VRow>

                    </VCardText>

                </VCard>

                <VRow class="mt-2">
                    <VCol cols="12" class="text-right">
                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-viewer-rating' }">
                            {{ $t("back") }}
                        </VBtn>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>