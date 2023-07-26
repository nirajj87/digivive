<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import {  useRoute } from 'vue-router';
import { useContentAdvisoryStore } from '../view/ContentAdvisoryStore';
import { AdvisoryModel } from '../view/type'

const advisoryStore = useContentAdvisoryStore()
const route = useRoute();

const advisoryData = ref<AdvisoryModel>({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'advisory_management',
    name: 'advisory_management',
});

onBeforeMount(() => {

    let advisoryId = Number(route.params.id) ?? 0;

    advisoryStore.getDetailsAdvisory(advisoryId)
        .then((response: any) => {
            advisoryData.value = response.data;
        })
        .catch((error) => {
            console.log("Some Internal Server Error Occured");

        })
})

</script>


<template>
    <div>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>

            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("advisory") }}</h5>
                        <VCard border>
                            <VCardText>
                                <VRow>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("advisory_name") }}</p>
                                        <h5>{{ advisoryData.title }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <h5> {{ advisoryData.status == '1' ? 'Active' : (advisoryData.status == '0' ?
                                            'Inactive' : (advisoryData.status == '2' ? 'Blocked' : 'Status Does not exist'))
                                        }} </h5>
                                        <!-- <VChip label :color="advisoryData.status == '1' ? 'success' : (advisoryData.status == '0' ? 'warning' : (advisoryData.status == '2' ? 'secondary' : 'primary'))" size="small"
                                            class="text-capitalize">
                                            <span>{{ advisoryData.status == '1' ? 'Active' : (advisoryData.status == '0' ? 'Inactive' : (advisoryData.status == '2' ? 'Blocked' : 'Status Does not exist')) }}</span>
                                        </VChip> -->
                                    </VCol>
                                </VRow>
                                <VRow class="mt-2">
                                    <VCol cols="12" class="text-right">
                                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-content-advisory' }">
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
</div>
</template>
