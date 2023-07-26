<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { ref, watchEffect } from 'vue';
import type { DateList } from './../view/types';
import { useDateStore } from './../view/useDateStore';
import { useRoute } from 'vue-router';

// 👉 Store
const dateListStore = useDateStore()
const dateList = ref<DateList>()

const route = useRoute();
const isLoading = ref(true)

// 👉 Fetching Details
const fetchDetails = () => {

    return new Promise((resolve, reject) => {
        isLoading.value = true
        let platformId = Number(route.params.id) ?? 0;
        if (platformId) {
            dateListStore.fetchDetails(platformId)
                .then((response: any) => {
                    isLoading.value = false
                    dateList.value = response.data

                    resolve(dateList);
                })
                .catch(error => {
                    console.error(error);
                    reject(dateList);
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
    <div>
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
                                    <h5>{{ dateList?.title }}</h5>
                                </VCol>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("date_format") }}</p>
                                    <h5>{{ dateList?.format_key }}</h5>
                                </VCol>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("order") }}</p>
                                    <h5>{{ dateList?.order }}</h5>
                                </VCol>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("status") }}</p>
                                    <h5>{{ dateList?.status == '1' ? ($t('active')) : ($t('inactive')) }}</h5>
                                </VCol>

                                <VCol cols="12" class="text-center" v-if="isLoading">
                                    <div class="loading-logo">
                                        <PageLoader> </PageLoader>
                                    </div>
                                    <span v-show="!dateList && !isLoading">{{ $t('no_data_found') }}</span>
                                </VCol>

                            </VRow>

                        </VCardText>

                    </VCard>

                    <VRow class="mt-2">
                        <VCol cols="12" class="text-right">
                            <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-date-formats' }">
                                {{ $t("back") }}
                            </VBtn>
                        </VCol>
                    </VRow>
                </VCardText>
            </VCard>
        </section>
    </div>
</template>

<style lang="scss">
.text-capitalize {
    text-transform: capitalize;
}
</style>