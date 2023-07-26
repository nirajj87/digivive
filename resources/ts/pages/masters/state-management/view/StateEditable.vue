<script lang="ts" setup>
import { ref } from 'vue';
import type { VForm } from 'vuetify/components'
import { requiredValidator } from '@validators'
import { useStateStore } from './StateStore';
import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';
import { StateModel } from './type';


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
    stateData: StateModel,
    countryList: Array<object>
}


// defining props
const props = defineProps<Props>();


console.log('In editable file : ', props.stateData);

// Form Errors
const errors = ref<Record<string, string | undefined>>({
    title: undefined,
    country_id: undefined,
    status: undefined,
})


const ContentStateStore = useStateStore();

const refForm = ref<VForm>();

const handleSubmit = () => {


    if (refForm.value) {

        console.log("Country Data : ",props.stateData);
        

        refForm.value.validate().then(({ valid }) => {
            if (valid) {
                console.log('Every thing is ok');
                console.log('Data : ', props.stateData);


                const moduleId: any = route.params.id ? route.params.id : 0;

                // updating State
                if (moduleId) {

                    ContentStateStore.updateState(moduleId, props.stateData)
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'
                            setTimeout(() => {
                                router.push({
                                    name: "masters-state-management"
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
                //adding new State
                else {
                    console.log('Adding');

                    ContentStateStore.addState(props.stateData)
                        .then((response: any) => {
                            isSnackbarVisibileDialog.value = true;
                            snackbarMessage.value = response.message;
                            snackbarClass.value = 'success-snackbar'

                            setTimeout(() => {
                                isSnackbarVisibileDialog.value = false;
                                router.push({
                                    name: "masters-state-management"
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
        <h5 v-show="!route.params.id" class="mb-4">{{  $t("add") }} {{ $t("state") }}</h5>
        <h5 v-show="route.params.id" class="mb-4">{{  $t("edit") }} {{ $t("state") }}</h5>


        <VSnackbar v-model="isSnackbarVisibileDialog" location="top right" :class="snackbarClass"> {{
            snackbarMessage }} </VSnackbar>

        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="handleSubmit()">
            <!-- <VForm ref="refForm" v-model="isFormValid" > -->
            <VRow>
                <VCol cols="12">
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField v-model="props.stateData.title" :rules="[requiredValidator]"
                                        :label="$t('state')" />
                                </VCol>

                                                                
                                <VCol cols="12" md="6">
                                    
                                        <VAutocomplete
                                            :rules="[requiredValidator]"
                                            v-model="props.stateData.country_id"
                                            :items="props.countryList"
                                            :label="$t('country_name')"
                                            clearable
                                            :menu-props="{maxHeight: '300'}"
                                        />
                                </VCol>


                                <VCol cols="12" md="6">
                                    <VTextField v-model="props.stateData.state_code" :rules="[requiredValidator]"
                                        :label="$t('state_code')" />
                                </VCol>
                                

                                <VCol cols="12" md="6">
                                    <VTextField v-model="props.stateData.latitude" :rules="[requiredValidator]"
                                        :label="$t('latitude')" />
                                </VCol>
                                

                                <VCol cols="12" md="6">
                                    <VTextField v-model="props.stateData.longitude" :rules="[requiredValidator]"
                                        :label="$t('longitude')" />
                                </VCol>


                                <VCol cols="12" md="6">
                                    <VSelect :rules="[requiredValidator]" v-model="props.stateData.status"
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
                    <VBtn :to="{ name: 'masters-state-management' }" class="mr-3" color="secondary" >
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