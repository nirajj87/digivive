<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRouter, useRoute } from 'vue-router';
import { useCityStore } from '../view/CityStore';
import { useStateStore } from '../../state-management/view/StateStore'
import { useCountryStore } from '../../country-management/view/CountryStore'
import { CityModel } from '../view/type'

const cityStore = useCityStore();
const stateStore = useStateStore();
const countryStore = useCountryStore();
const route = useRoute();
const router = useRouter();

const snackbarMessage = ref('')
const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('');

const stateName = ref("");
const countryName = ref("");

const cityData = ref<CityModel>({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'city_management',
    name: 'city_management',
});

onBeforeMount(() => {

    let cityId = Number(route.params.id) ?? 0;

    cityStore.getDetailsCity(cityId)
        .then((response: any) => {
            cityData.value = response.data;
        })
        .catch((error) => {
            console.log("Some Internal Server Error Occured");
            isSnackbarVisibileDialog.value = true;
            snackbarMessage.value = 'internal-server-error';
            snackbarClass.value = 'delete-snackbar';
            setTimeout(() => {
                router.push({ name: 'masters-city-management' })
            }, 2000);

        })

})


</script>


<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <VSnackbar v-model="isSnackbarVisibileDialog" location="top right" :class="snackbarClass"> {{
            $t(`${snackbarMessage}`) }} </VSnackbar>

        <VCard>

            <VCardText>
                <VRow>
                    <VCol cols="12">
                        <h5 class="mb-2">{{ $t("city") }}</h5>
                        <VCard border>
                            <VCardText>
                                <VRow>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("city_name") }}</p>
                                        <h5>{{ cityData.title }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("state") }}</p>
                                        <h5>{{ cityData.state_name }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("country") }}</p>
                                        <h5>{{ cityData.country }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("latitude") }}</p>
                                        <h5>{{ cityData.latitude }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("longitude") }}</p>
                                        <h5>{{ cityData.longitude }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <h5> {{ cityData.status == '1' ? 'Active' : (cityData.status == '0' ? 'Inactive' :
                                            (cityData.status == '2' ? 'Blocked' : 'Status Does not exist')) }} </h5>

                                    </VCol>
                                </VRow>

                                <VRow class="mt-2">
                                    <VCol cols="12" class="text-right">
                                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-city-management' }">
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
</template>


<style>
.success-snackbar .v-snackbar--variant-elevated {
    background: #E5F3EE !important;
    color: #444;
}

.delete-snackbar .v-snackbar--variant-elevated {
    background: rgb(var(--v-theme-error)) !important;
    opacity: 0.8;
}

.success-snackbar .v-overlay__content {
    top: 120px !important;
}
</style>