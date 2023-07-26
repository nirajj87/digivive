<script setup lang="ts">
import PlatformEditable from '../view/PlatformEditable.vue'
import { PlatformModel } from '../view/type';
import { ref } from 'vue';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';


const platformParams = ref<PlatformModel>({
    title: '',
})


const breadcrumbsData = ref({
    page_name: 'add_platform',
    name: 'add_platform',
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
                        <PlatformEditable :platform-data="platformParams" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>