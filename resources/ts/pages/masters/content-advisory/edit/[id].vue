<script setup lang="ts">
import AdvisoryEditable from '../view/ContentAdvisoryEditable.vue'
import { useContentAdvisoryStore } from '../view/ContentAdvisoryStore';
import { useRoute, useRouter } from 'vue-router';
import { AdvisoryModel } from '../view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const advisoryStore = useContentAdvisoryStore()
const route = useRoute();
const router = useRouter();

const advisoryData = ref<AdvisoryModel>({
    title: ''
})

watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (moduleId) {
        advisoryStore.getDetailsAdvisory(moduleId)
            .then((response: any) => {
                if (response) {
                    advisoryData.value = response.data;
                }
            })
            .catch((e) => {
                console.log("Some internal server error occured");
                router.push({ name: 'masters-content-advisory' })

            })
    }
})


const breadcrumbsData = ref({
    page_name: 'edit_advisory',
    name: 'edit_advisory',
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
                        <AdvisoryEditable :advisory-data="advisoryData" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>