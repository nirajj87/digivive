<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRoute } from 'vue-router';
import { useAdsStore } from '../view/AdsStore';
import { AdsModel } from '../view/type'

const AdsStore = useAdsStore()
const route = useRoute();

const isLoading = ref(true);

const adsData = ref<AdsModel>({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'ads_management',
    name: 'ads_management',
});

onBeforeMount(() => {

    let AdsId = Number(route.params.id) ?? 0;


    AdsStore.getDetailsAds(AdsId)
        .then((response: any) => {
            adsData.value = response.data;
            isLoading.value = false ;
        })
        .catch((error) => {
            console.log("Some Internal Server Error Occured");

        })

})


// 👉 getting status word
const statusWord = (stat: any) => {
    if (stat === '0')
        return 'inactive'
    if (stat === '1')
        return 'active'
    if (stat === '2')
        return 'blocked'
    return 'primary'
}

</script>


<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>

            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("ads") }}</h5>
                        <VCard border>
                            <VCardText>
                                <PageLoader v-if="isLoading"></PageLoader>
                                <VRow  v-if="!isLoading">
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("ads_name") }}</p>
                                        <h5>{{ adsData.title }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <!-- <h5> {{ adsData.status == '1' ? 'Active' : (adsData.status == '0' ?
                                            'Inactive' : (adsData.status == '2' ? 'Blocked' : 'Status Does not exist'))
                                        }} </h5> -->

                                        <h5> {{ $t(statusWord(adsData.status)) }} </h5>
                                    </VCol>
                                </VRow>
                                <VRow class="mt-2">
                                    <VCol cols="12" class="text-right">
                                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-ads-management' }">
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
    </section>
</template>
