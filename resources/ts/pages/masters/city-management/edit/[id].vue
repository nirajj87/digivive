<script setup lang="ts">
import { useCityStore } from '../view/CityStore';
import { useRoute, useRouter } from 'vue-router';
import { CityModel } from '../view/type'
import CityEditable from '../view/CityEditable.vue';
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';

const CityStore = useCityStore()
const route = useRoute();
const router = useRouter();

const cityData = ref<CityModel>({
    title: '',
    latitude: '',
    longitude: '',
})

onBeforeMount(() => {
    let moduleId = Number(route.params.id) ?? 0;

    if (moduleId) {
        CityStore.getDetailsCity(moduleId)
            .then((response: any) => {
                if (response) {
                    cityData.value = response.data;

                }
            })
            .catch((e) => {
                console.log("Some internal server error occured");
                router.push({ name: 'masters-city-management' })

            })
    }
})


const breadcrumbsData = ref({
    page_name: 'edit_city',
    name: 'edit_city',
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
                        <CityEditable :city-data="cityData" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </div>
</template>