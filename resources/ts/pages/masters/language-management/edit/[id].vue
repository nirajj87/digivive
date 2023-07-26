<script setup lang="ts">
import { useLanguageStore } from '../view/LanguageStore';
import { useRoute, useRouter } from 'vue-router';
import { LanguageModel } from '../view/type'
import LanguageEditable from '../view/LanguageEditable.vue';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const languageStore = useLanguageStore()
const route = useRoute();
const router = useRouter();

const languageData = ref<LanguageModel>({
    title: ''
})

watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (moduleId) {
        languageStore.getDetailsLanguage(moduleId)
            .then((response: any) => {
                if (response) {
                    languageData.value = response.data;
                }
            })
            .catch((e) => {
                console.log("Some internal server error occured");
                router.push({ name: 'masters-language-management' })

            })
    }
})


const breadcrumbsData = ref({
    page_name: 'edit_platform',
    name: 'edit_platform',
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
                        <LanguageEditable :language-data="languageData" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>