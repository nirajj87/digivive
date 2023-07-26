<script lang="ts" setup>
import { ref } from 'vue';
import type { VForm } from 'vuetify/components'
import { requiredValidator } from '@validators'
import { useAnalyticsStore } from './AnalyticsStore';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';
import { AnalyticsModel } from './type';


const snackbarMessage = ref('')
const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('');


const isFormValid = ref(false)

//getting router variable
const router = useRouter();
const route = useRoute();

const items = [
    { title: 'active', value: '1' },
    { title: 'inactive', value: '0' },
]


// defining props type
interface Props {
    analyticsData: AnalyticsModel
}

// defining props
const props = defineProps<Props>();

// Form Errors
const errors = ref<Record<string, string | undefined>>({
    title: undefined,
    status: undefined,
})


const ContentAnalyticsStore = useAnalyticsStore();

const refForm = ref<VForm>();

const handleSubmit = () => {


    if (refForm.value) {

        refForm.value.validate().then(({ valid }) => {
            if (valid) {
                console.log('Every thing is ok');

                const moduleId: any = route.params.id ? route.params.id : 0;

                // updating Analytics
                if (moduleId) {

                    ContentAnalyticsStore.updateAnalytics(moduleId, props.analyticsData )
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'
                            setTimeout(() => {
                                router.push({
                                    name: "masters-analytics-management"
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
                //adding new Analytics
                else {
                    console.log('Adding');

                    ContentAnalyticsStore.addAnalytics( props.analyticsData)
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'

                            setTimeout(() => {
                                isSnackbarVisibileDialog.value = false;
                                router.push({
                                    name: "masters-analytics-management"
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
    else {
        console.log('Data mistake : ', refForm.value);
        console.log('Error is : ', errors.value);

    }

}

</script>


<template>
    <section>
        <h5 v-show="!route.params.id" class="mb-4">{{ $t("add") }} {{ $t("analytics") }}</h5>
        <h5 v-show="route.params.id" class="mb-4">{{ $t("edit") }} {{ $t("analytics") }}</h5>

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
                                    <VTextField v-model="props.analyticsData.title" :rules="[requiredValidator]"
                                        :label="$t('analytics')" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VSelect :rules="[requiredValidator]" v-model="props.analyticsData.status"
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
                    <VBtn class="mr-3" color="secondary" :to="{ name: 'masters-analytics-management' }" >
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