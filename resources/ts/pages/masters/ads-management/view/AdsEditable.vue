<script lang="ts" setup>
import { ref } from 'vue';
import type { VForm } from 'vuetify/components'
import { requiredValidator } from '@validators'
import { useAdsStore } from './AdsStore';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';
import { AdsModel } from './type';

//getting router variable
const snackbarMessage = ref('')
const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('')

const isFormValid = ref(false)
const router = useRouter();
const route = useRoute();

const items = [
    { title: 'active', value: '1' },
    { title: 'inactive', value: '0' },
]


// defining props type
interface Props {
    adsData: AdsModel
}


// Form Errors
const errors = ref<Record<string, string | undefined>>({
    title: undefined,
    status: undefined,
})

// defining props
const props = defineProps<Props>();

const ContentAdsStore = useAdsStore();

const refForm = ref<VForm>();

const handleSubmit = () => {


    if (refForm.value) {

        refForm.value.validate().then(({valid}) => {
            if (valid) {

                const moduleId: any = route.params.id ? route.params.id : 0;

                // updating Ads
                if (moduleId) {

                    ContentAdsStore.updateAds(moduleId, {
                        'title': props.adsData.title,
                        'status': props.adsData.status
                    })
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'
                            setTimeout(() => {
                                router.push({
                                    name: "masters-ads-management"
                                })
                            }, 2000);
                        })
                        .catch((error) => {
                            if (error.response.data.message) {
                                isSnackbarVisibileDialog.value = true;
                                snackbarMessage.value = 'internal-server-error';
                                snackbarClass.value = 'delete-snackbar'
                            }
                        })
                }
                //adding new Ads
                else {

                    ContentAdsStore.addAds(props.adsData)
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'

                            setTimeout(() => {
                                isSnackbarVisibileDialog.value = false;
                                router.push({
                                    name: "masters-ads-management"
                                })
                            }, 2000);
                        })
                        .catch((error) => {

                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = 'internal-server-error';
                            snackbarClass.value = 'delete-snackbar'
                        })
                }
            }
        })
    }
}

</script>


<template>
    <section>
        <h5 v-show="!route.params.id" class="mb-4">{{  $t("add") }} {{ $t("ads") }}</h5>
        <h5 v-show="route.params.id" class="mb-4">{{  $t("edit") }} {{ $t("ads") }}</h5>

        <VSnackbar v-model="isSnackbarVisibileDialog" location="top right" :class="snackbarClass"> {{
            snackbarMessage }} </VSnackbar>

        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="handleSubmit()">
            <!-- <VForm ref="refForm" v-model="isFormValid" > -->
            <VRow>
                <VCol cols="12">
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.adsData.title" :rules="[requiredValidator]"
                                        :label="$t('ads')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VSelect :rules="[requiredValidator]" v-model="props.adsData.status"
                                        :items="items.map((item) => { return { 'title': $t(item.title), 'value': item.value } })"
                                        :label="$t('status')" />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>
            <VRow>
                <VCol cols="12" class="text-right">
                    <VBtn class="mr-3" color="secondary" :to="{name : 'masters-ads-management'}">
                        {{ $t("can_btn") }}
                    </VBtn>
                    <VBtn  type="submit" @click="refForm?.validate()">
                        <ButtonLoader v-show="isSnackbarVisibileDialog"></ButtonLoader>
                        {{ $t("sub_btn") }}
                    </VBtn>
                </VCol>
            </VRow>
        </VForm>
    </section>
</template>

<style>

</style>