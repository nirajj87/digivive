<script setup lang="ts">
import AdsEditable from '../view/AdsEditable.vue'
import { AdsModel } from '../view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';


const AdsParams = ref<AdsModel>({
    title: '',
})


const breadcrumbsData = ref({
    page_name: 'add_ads',
    name: 'add_ads',
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
                        <AdsEditable :ads-data="AdsParams" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>

    </div>
</template>