<script lang="ts" setup>
import { ref } from 'vue';
import type { VForm } from 'vuetify/components'
import { requiredValidator } from '@validators'
import { useLanguageStore } from './LanguageStore';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';
import { LanguageModel } from './type';
import { useCountryStore } from '../../country-management/view/CountryStore'

//getting store variable
const countryStore = useCountryStore();

const snackbarMessage = ref('')
const isSnackbarVisibileDialog = ref(false)
const snackbarClass = ref('');

const country_code = ref();
const countryList = ref([{
    title: '',
    value: ''
}])

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
    languageData: LanguageModel
}

// defining props
const props = defineProps<Props>();

// Form Errors
const errors = ref<Record<string, string | undefined>>({
    title: undefined,
    iso_code2: undefined,
    iso_code3: undefined,
    country_code: undefined,
    status: undefined,
})


const ContentLanguageStore = useLanguageStore();

const refForm = ref<VForm>();

const handleSubmit = () => {


    if (refForm.value) {

        refForm.value.validate().then(({ valid }) => {
            if (valid) {
                console.log('Every thing is ok');

                const moduleId: any = route.params.id ? route.params.id : 0;

                // updating Language
                if (moduleId) {

                    ContentLanguageStore.updateLanguage(moduleId, { ...props.languageData, 'country_code': country_code.value })
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'
                            setTimeout(() => {
                                router.push({
                                    name: "masters-language-management"
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
                //adding new Language
                else {
                    console.log('Adding');

                    ContentLanguageStore.addLanguage({ ...props.languageData, 'country_code': country_code.value })
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'

                            setTimeout(() => {
                                isSnackbarVisibileDialog.value = false;
                                router.push({
                                    name: "masters-language-management"
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

const handleCancel = () => {
    router.push({ name: 'masters-language-management' })
}

onBeforeMount(() => {
    countryStore.fetchAllCountry({ "perPage": -1 })
        .then((response: any) => {
            countryList.value = response.data.map((element: any) => {
                return { 'title': element.title, 'value': element.iso2 }
            });

            if(route.params.id){
                country_code.value = props.languageData.country_code
            }

        }).catch((error) => {
            console.log("Some Internal Server Error Occured!");

        })
})

</script>


<template>
    <section>
        <h5 v-show="!route.params.id" class="mb-4">{{ $t("add") }} {{ $t("language") }}</h5>
        <h5 v-show="route.params.id" class="mb-4">{{ $t("edit") }} {{ $t("language") }}</h5>

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
                                    <VTextField v-model="props.languageData.title" :rules="[requiredValidator]"
                                        :label="$t('language')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.languageData.iso_code2" :rules="[requiredValidator]"
                                        :label="$t('iso_code2')" />
                                </VCol>
                                <VCol cols="12" md="4">
                                    <VTextField v-model="props.languageData.iso_code3" :rules="[requiredValidator]"
                                        :label="$t('iso_code3')" />
                                </VCol>
                                <VCol cols="12" md="4">

                                    <VAutocomplete :rules="[requiredValidator]" v-model="country_code" :items="countryList"
                                        :label="$t('country')" clearable :menu-props="{ maxHeight: '300' }" />
                                </VCol>

                                <VCol cols="12" md="4">
                                    <VSelect :rules="[requiredValidator]" v-model="props.languageData.status"
                                        :items="items.map((item) => { return { 'title': $t(item.title), 'value': $t(item.value) } })"
                                        :label="$t('status')" />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>
            <VRow>
                <VCol cols="12" class="text-right">
                    <VBtn class="mr-3" color="secondary" @click="handleCancel()">
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