<script setup lang="ts">
import AdsEditable from '../view/AdsEditable.vue'
import { useAdsStore } from '../view/AdsStore';
import { useRoute, useRouter } from 'vue-router';
import { AdsModel } from '../view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const AdsStore = useAdsStore()
const route = useRoute();
const router = useRouter();

const adsData = ref<AdsModel>({
    title: ''
})

watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (moduleId) {
        AdsStore.getDetailsAds(moduleId)
            .then((response: any) => {
                if (response) {
                    adsData.value = response.data;
                }
            })
            .catch((e) => {
                console.log("Some internal server error occured");
                router.push({ name: 'masters-content-Ads' })

            })
    }
})


const breadcrumbsData = ref({
    page_name: 'edit_ads',
    name: 'edit_ads',
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
                        <AdsEditable :ads-data="adsData" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>