<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { ref, watchEffect } from 'vue';
import type { BitframeList } from './../view/types';
import { useBitframeStore } from './../view/useBitframeStore';
import { useRoute } from 'vue-router';

// 👉 Store
const bitframeListStore = useBitframeStore()
const bitframeList = ref<BitframeList>()

const route = useRoute();
const isLoading = ref(true)

// 👉 Fetching Details
const fetchDetails = () => {
    return new Promise((resolve, reject) => {
        isLoading.value = true
        let bitframeId = Number(route.params.id) ?? 0;
        if (bitframeId) {
            bitframeListStore.fetchDetails(bitframeId)
                .then((response: any) => {
                    isLoading.value = false
                    bitframeList.value = response.data

                    resolve(bitframeList);
                })
                .catch(error => {
                    console.log("Error in Details===>", error);
                    reject(bitframeList);
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
                                <h5>{{ bitframeList?.type }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("presets") }}</p>
                                <h5>{{ bitframeList?.presets }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("download_type") }}</p>
                                <h5>{{ bitframeList?.download_type }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("isDownloadable") }}</p>
                                <h5>{{ bitframeList?.is_downloadable == '1' ? ($t('yes')) : ($t('no')) }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("status") }}</p>
                                <h5>{{ bitframeList?.status == '1' ? ($t('active')) : ($t('inactive')) }}</h5>
                            </VCol>

                            <VCol cols="12" class="text-center" v-if="isLoading">
                                <div class="loading-logo">
                                    <PageLoader> </PageLoader>
                                </div>
                                <span v-show="!bitframeList && !isLoading">{{ $t('no_data_found') }}</span>
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>

                <VRow class="mt-2">
                    <VCol cols="12" class="text-right">
                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-bitframe' }">
                            {{ $t("back") }}
                        </VBtn>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>