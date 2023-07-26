<script setup lang="ts">
import PlatformEditable from '../view/PlatformEditable.vue'
import { usePlatformStore } from '../view/PlatformStore';
import { useRoute, useRouter } from 'vue-router';
import { PlatformModel } from '../view/type'
import { watchEffect, ref } from 'vue';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';


const platformStore = usePlatformStore()
const route = useRoute();
const router = useRouter();

const platformData = ref<PlatformModel>({
    title: '',
})

watchEffect(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (moduleId) {
        platformStore.getDetailsPlatform(moduleId)
            .then((response: any) => {
                if (response) {
                    platformData.value = response.data;
                }
            })
            .catch((e) => {
                console.log("Some internal server error occured");
                router.push({ name: 'masters-platform-management' })

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
                        <PlatformEditable :platform-data="platformData" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>