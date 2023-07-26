<script setup lang="ts">
import LanguageEditable from '../view/LanguageEditable.vue'
import { LanguageModel } from '../view/type';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';


const languageParams = ref<LanguageModel>({
    title: '',
    iso_code2: '',
    iso_code3: '',
    country_code: '',
})


const breadcrumbsData = ref({
    page_name: 'add_language',
    name: 'add_language',
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
                        <LanguageEditable :language-data="languageParams" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>