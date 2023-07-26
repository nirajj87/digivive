<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRouter, useRoute } from 'vue-router';
import { useLanguageStore } from '../view/LanguageStore';
import { LanguageModel } from '../view/type'

const languageStore = useLanguageStore()
const route = useRoute();

const languageData = ref<LanguageModel>({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'language_management',
    name: 'language_management',
});

onBeforeMount(() => {

    let languageId = Number(route.params.id) ?? 0;


    languageStore.getDetailsLanguage(languageId)
        .then((response: any) => {
            languageData.value = response.data;
        })
        .catch((error) => {
            console.log("Some Internal Server Error Occured");

        })

})


</script>


<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
        <VCard>

            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("platform") }}</h5>
                        <VCard border>
                            <VCardText>
                                <VRow>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("language_name") }}</p>
                                        <h5>{{ languageData.title }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("country_code") }}</p>
                                        <h5>{{ languageData.country_code ? languageData.country_code : "Not Available" }}
                                        </h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("iso_code2") }}</p>
                                        <h5>{{ languageData.iso_code2 ? languageData.iso_code2 : "Not Available" }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("iso_code3") }}</p>
                                        <h5>{{ languageData.iso_code3 ? languageData.iso_code3 : "Not Available" }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <h5> {{ languageData.status == '1' ? 'Active' : (languageData.status == '0' ?
                                            'Inactive' : (languageData.status == '2' ? 'Blocked' : 'Status Does not exist'))
                                        }} </h5>

                                    </VCol>
                                </VRow>

                                <VRow class="mt-2">
                                    <VCol cols="12" class="text-right">
                                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-language-management' }">
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
    </section></template>
