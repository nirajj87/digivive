<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRouter, useRoute } from 'vue-router';
import { usePlatformStore } from '../view/PlatformStore';
import { PlatformModel } from '../view/type'


const platformStore = usePlatformStore()
const router = useRouter();
const route = useRoute();


const platformData = ref<PlatformModel>({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'platform_management',
    name: 'platform_management',
});

onBeforeMount(() => {

    let platformId = Number(route.params.id) ?? 0;


    platformStore.getDetailsPlatform(platformId)
        .then((response: any) => {
            platformData.value = response.data;
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
                                        <p class="mb-0">{{ $t("platform_name") }}</p>
                                        <h5>{{ platformData.title }}</h5>
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <h5> {{ platformData.status == '1' ? 'Active' : (platformData.status == '0' ?
                                            'Inactive' : (platformData.status == '2' ? 'Blocked' : 'Status Does not exist'))
                                        }} </h5>
                                     
                                    </VCol>
                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>
                </VRow>

                <VRow class="mt-2">
                    <VCol cols="12" class="text-right">
                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-platform-management' }">
                            {{ $t("back") }}
                        </VBtn>
                    </VCol>
                </VRow>

            </VCardText>
        </VCard>
    </section>
</template>