<script setup lang="ts">
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import { useRouter, useRoute } from 'vue-router';
import { useStateStore } from '../view/StateStore';
import { StateModel } from '../view/type';
import { useCountryStore } from '../../country-management/view/CountryStore'

const stateStore = useStateStore();
const countryStore = useCountryStore();

const router = useRouter();
const route = useRoute();

const snackbarMessage = ref('')
const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('');

const isLoading = ref(false);

const countryName = ref("");

const stateData = ref<StateModel>({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'state_management',
    name: 'state_management',
});

onBeforeMount(() => {

    let stateId = Number(route.params.id) ?? 0;
    isLoading.value = true;

    stateStore.getDetailsState(stateId)
        .then((response: any) => {
            stateData.value = response.data;

            return countryStore.getDetailsCountry(response.data.country_id);
        })
        .then((response: any) => {
            countryName.value = response.data.title;

            isLoading.value = false;
        })
        .catch((error) => {
            console.log("Some Internal Server Error Occured");

            isSnackbarVisibileDialog.value = true;
            snackbarMessage.value = 'internal-server-error';
            snackbarClass.value = 'delete-snackbar';
            setTimeout(() => {
                router.push({ name: 'masters-state-management' })
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
                        <h5 class="mb-2">{{ $t("state") }}</h5>
                        <VCard border>




                            <VCardText>

                                <VRow v-show="isLoading">
                                    <PageLoader></PageLoader>
                                </VRow>



                                <VRow v-show="!isLoading">

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("state_name") }}</p>
                                        <h5>{{ stateData.title }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("country") }}</p>
                                        <h5>{{ countryName }}</h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("state_code") }}</p>
                                        <h5>{{ stateData.state_code }}</h5>
                                    </VCol>


                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("latitude") }}</p>
                                        <h5> {{ stateData.latitude }} </h5>
                                    </VCol>

                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("longitude") }}</p>
                                        <h5> {{ stateData.longitude }} </h5>
                                    </VCol>



                                    <VCol cols="12" md="6">
                                        <p class="mb-0">{{ $t("status") }}</p>
                                        <h5> {{ stateData.status == '1' ? 'Active' : (stateData.status == '0' ? 'Inactive' :
                                            (stateData.status == '2' ? 'Blocked' : 'Status Does not exist')) }} </h5>
                                    </VCol>

                                </VRow>

                                <VRow class="mt-2">
                                    <VCol cols="12" class="text-right">
                                        <VBtn prepend-icon="tabler-chevron-left" :to="{ name: 'masters-state-management' }">
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