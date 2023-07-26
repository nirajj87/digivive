<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { ref, watchEffect } from 'vue';
import type { PaymentList } from './../view/types';
import { usePaymentStore } from './../view/usePaymentStore';
import { useRoute } from 'vue-router';

// 👉 Store
const paymentListStore = usePaymentStore()
const paymentList = ref<PaymentList>()

const route = useRoute();
const isLoading = ref(true)

// 👉 Fetching Details
const fetchDetails = () => {
    return new Promise((resolve, reject) => {
        isLoading.value = true
        let bitframeId = Number(route.params.id) ?? 0;
        if (bitframeId) {
            paymentListStore.fetchDetails(bitframeId)
                .then((response: any) => {
                    isLoading.value = false
                    paymentList.value = response.data

                    resolve(paymentList);
                })
                .catch(error => {
                    console.log("Error in Details===>", error);
                    reject(paymentList);
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
                                <h5>{{ paymentList?.title }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("type") }}</p>
                                <h5>{{ paymentList?.type }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("slug") }}</p>
                                <h5>{{ paymentList?.slug }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("recurring") }}</p>
                                <h5>{{ paymentList?.is_recurring == '1' ? ($t('yes')) : ($t('no')) }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("additional_settings") }}</p>
                                <h5>{{ paymentList?.additional_settings }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("image") }}</p>
                                <h5>{{ paymentList?.image }}</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("status") }}</p>
                                <h5>{{ paymentList?.status == '1' ? ($t('active')) : ($t('inactive')) }}</h5>
                            </VCol>

                            <VCol cols="12" class="text-center" v-if="isLoading">
                                <div class="loading-logo">
                                    <PageLoader> </PageLoader>
                                </div>
                                <span v-show="!paymentList && !isLoading">{{ $t('no_data_found') }}</span>
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>

                <VRow class="mt-2">
                    <VCol cols="12" class="text-right">
                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-payment-mode' }">
                            {{ $t("back") }}
                        </VBtn>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>