<script setup lang="ts">
import AdvisoryEditable from '../view/ContentAdvisoryEditable.vue'
import { AdvisoryModel } from '../view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';


const advisoryParams = ref<AdvisoryModel>({
    title: '',
})


const breadcrumbsData = ref({
    page_name: 'add_advisory',
    name: 'add_advisory',
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
                        <AdvisoryEditable :advisory-data="advisoryParams" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>

    </div>
</template>