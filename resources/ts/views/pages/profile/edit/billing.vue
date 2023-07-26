<script lang="ts" setup>
import checkimg from '@images/pages/cancelled_cheque.jpg'
import { lengthValidator, requiredValidator, fileSizeValidator } from '@validators'
import router from '@/router'
import { userBillingStore } from '@/views/pages/profile/billingStore'
import type { billingData } from './../types';
import type { VForm } from 'vuetify/components';
import { Billing } from '../types'
// 👉 Store
const useBillingStore = userBillingStore()

const base_url = window.location.origin.replace(window.location.port, '5173') + '/storage/app/public/';


const bankData = ref<Billing>({});
const refInputEl = ref<HTMLElement>()

const chequeImage = ref<File | null | string>(null);
const chequeImageLocal = ref<File | null | string>(null);
const isImageChange = ref(false)
const isLoading = ref(true)
const route = useRoute()
const userId = Number(route.params.userid ? route.params.userid : route.params.uid) ?? 0;

const gstin_image = ref<File | null>(null)
const cin_image = ref<File | null>(null)
const pan_image = ref<File | null>(null)

const isSnackbarVisibileDialog = ref(false);
const snackbarClass = ref('')
const snackBarMessage = ref('');


const changeChequeImage = (file: Event) => {

    console.log('cheque funtion called :');

    const { files } = file.target as HTMLInputElement;
    const reader = new FileReader();

    chequeImage.value = files[0];

    isImageChange.value = true;

    if (files && files.length) {

        reader.readAsDataURL(file.target.files[0]);
        reader.onload = e => {
            chequeImageLocal.value = e.target.result;
        }

    }

    // console.log('Local cheque image : ',chequeImageLocal.value);
    // console.log('Original cheque image : ',chequeImage.value);


}

const uploadImage = (file: Event) => {

    const fileReader = new FileReader()
    const { files } = file.target as HTMLInputElement;
    console.log('Incoming file is : ', files);

    if (file.target && file.target.name == 'gstin_image') {
        gstin_image.value = files[0];
    }
    else if (file.target.name == 'cin_image') {
        cin_image.value = files[0];
    }
    else if (file.target.name == 'pan_image') {
        pan_image.value = files[0];
    }

}

const resetChequeImage = () => {
    console.log('coming in reset');

    chequeImage.value = null;
    chequeImageLocal.value = null;
    bankData.value.cheque = '';
    isImageChange.value = false;

}
const errors = ref<Record<string, string | undefined>>({
    address_line_1: undefined,
    account_number: undefined,
    beneficiary_name: undefined,
    bank_name: undefined,
    ifsc_code: undefined,
    bank_address: undefined,
    cancelled_cheque: undefined,
    gstin: undefined,
    cin: undefined,
    pan: undefined,
    swift_code: undefined,
    user_id: undefined,
})


const onSubmit = () => {

    if (userId) {

        const formData = new FormData();
        formData.append('billing', JSON.stringify(bankData.value));
        formData.append('user_id', userId.toString());
        gstin_image.value ? formData.append('gstin_image', gstin_image.value) : '';
        cin_image.value ? formData.append('cin_image', cin_image.value) : '';
        pan_image.value ? formData.append('pan_image', pan_image.value) : '';
        chequeImage.value ? formData.append('cheque', chequeImage.value) : '';

        console.log('Form Data : ', formData);

        useBillingStore.updateBillingDetails(formData)
            .then((response: any) => {

                console.log('Successfully updated ');
                isSnackbarVisibileDialog.value = true;
                snackBarMessage.value = response.message;
                snackbarClass.value = 'success-snackbar';

                setTimeout(() => {

                    router.push({ name: 'edit-profile-tab-uid', params: { tab: 'security', uid: userId.toString() } })
                }, 3000);
            })

            .catch((e) => {
                let res = e.response.data.data;
                isLoading.value = false;

                for (const key in res) {
                    for (const e_key in errors.value) {
                        if (key == e_key) {
                            errors.value[e_key] = res[key][0];
                            snackbarClass.value = 'delete-snackbar';
                            isSnackbarVisibileDialog.value = true;
                            snackBarMessage.value = res[key][0];
                            break;
                        }

                    }
                }

                isSnackbarVisibileDialog.value = true;
                snackbarClass.value = 'delete-snackbar';
                snackBarMessage.value = 'internal-server-error';

            })
    }

}

const refForm = ref<VForm>();

const onFormSubmit = () => {
    console.log(refForm.value);

    if (refForm.value) {

        refForm.value.validate().then(({ valid }) => {
            console.log('Valid Value : ', valid);

            if (valid) {
                console.log('All Required Box is filled');
                onSubmit()
            }
            else {
                console.log('No , Required Box is not filled');
                console.log('Data is : ', bankData.value);
            }

        })

    }

}


onBeforeMount(() => {

    useBillingStore.getBillingDetails(userId).then((response: any) => {
        console.log(' response.data by ashish', response.data);
        bankData.value = response.data.user_profile.billing;
        isLoading.value = false;
    }).catch((error) => {
        console.log('Internal server error : ', error);
        isSnackbarVisibileDialog.value = true;
        snackbarClass.value = 'delete-snackbar';
        snackBarMessage.value = 'internal-server-error';
        setTimeout(() => {
            router.push({ name: 'profile-tab-id', params: { tab: 'billing', id: userId } })
        }, 2000);
    })


})


</script>

<template>
    <div>

        <section>

            <!-- SnackBar -->
            <VSnackbar v-model="isSnackbarVisibileDialog" location="top end" :class="snackbarClass"> {{
                snackBarMessage }}
            </VSnackbar>

            <PageLoader v-show="isLoading"></PageLoader>

            <VCardText v-show="!isLoading" class="pt-0">
                <VForm ref="refForm" @submit.prevent="onFormSubmit" class="form-border">

                    <VRow>
                        <VCol cols="12">
                            <h5 class="mb-2">{{ $t("bank_detail") }}</h5>
                            <VCard border>
                                <VCardText>

                                    <VRow>
                                        <VCol cols="12" md="4">
                                            <VTextField :rules="[requiredValidator]" v-model="bankData.benificiary_name"
                                                :label="$t('beneficiary_name') + ' * '" />
                                        </VCol>
                                        <VCol cols="12" md="4">
                                            <VTextField :rules="[requiredValidator]" v-model="bankData.bank_name"
                                                :label="$t('bank_name') + ' * '" />
                                        </VCol>
                                        <VCol cols="12" md="4">
                                            <VTextField :rules="[requiredValidator]" v-model="bankData.account_number"
                                                :label="$t('account_no') + ' * '" />
                                        </VCol>
                                        <VCol cols="12" md="4">
                                            <VTextField :rules="[requiredValidator]" v-model="bankData.ifsc_code"
                                                :label="$t('ifsc_code')" />
                                        </VCol>
                                        <VCol cols="12" md="4">
                                            <!-- <VTextField :rules="[requiredValidator]" v-model="bankData.swift_code" -->
                                            <VTextField v-model="bankData.swift_code" :label="$t('swift_code')" />
                                        </VCol>
                                        <VCol cols="12" md="4">
                                            <VTextField :rules="[requiredValidator]" v-model="bankData.address"
                                                :label="$t('bank_branch')" />
                                        </VCol>
                                    </VRow>
                                </VCardText>
                            </VCard>
                        </VCol>
                        <VCol cols="12">
                            <h5 class="mb-2">{{ $t("cancelled_cheque") }}</h5>
                            <VCard border>
                                <VCardText>
                                    <VRow>
                                        <VCol cols="12">

                                            <!-- <VImg class="cheque_img" :src="checkimg" /> -->
                                            <!-- <VImg class="cheque_img" :src="isImageChange  ? chequeImageLocal : (bankData.cheque ?  base_url + bankData.cheque : checkimg)" /> -->
                                            <VImg class="cheque_img"
                                                :src="isImageChange ? chequeImageLocal : (bankData.cheque ? base_url + bankData.cheque : checkimg)" />



                                            <div class="d-flex flex-wrap gap-2">
                                                <VBtn color="primary" @click="refInputEl?.click()">
                                                    <VIcon icon="tabler-cloud-upload" class="d-sm-none" />
                                                    <span class="d-none d-sm-block">{{ $t("upload_cancelled_cheque")
                                                    }}</span>
                                                </VBtn>

                                                <!-- <input :rules="bankData.cheque ? [fileSizeValidator] : [requiredValidator , fileSizeValidator]" ref="refInputEl" type="file" name="file" accept=".jpeg,.png,.jpg,.webp"
                                                    hidden @input="changeChequeImage"> -->

                                                <!-- <VFileInput v-show="false" :class="'file-input'" :rules="bankData.cheque ? [fileSizeValidator] : [requiredValidator , fileSizeValidator]"  ref="refInputEl" type="file" name="file" accept=".jpeg,.png,.jpg,.webp" :show-size="1024"
                                                    :hidden="true" @change="changeChequeImage" /> -->

                                                <VFileInput class="hide_box_input_file" :rules="bankData.cheque ? [fileSizeValidator] : [requiredValidator , fileSizeValidator]"  ref="refInputEl" type="file" name="file" accept=".jpeg,.png,.jpg,.webp" :show-size="1024"
                                                     @change="changeChequeImage" />

                                                <VBtn type="button" color="secondary" variant="tonal"
                                                    @click="resetChequeImage">
                                                    <span class="d-none d-sm-block">{{ $t("reset") }}</span>
                                                    <VIcon icon="tabler-refresh" class="d-sm-none" />
                                                </VBtn>
                                            </div>
                                        </VCol>
                                    </VRow>
                                </VCardText>
                            </VCard>
                        </VCol>
                        <VCol cols="12">
                            <h5 class="mb-2">{{ $t("other_detail") }}</h5>
                            <VCard border>
                                <VCardText>

                                    <VRow>
                                        <VCol cols="12" md="4">
                                            <div class="d-flex position-relative">
                                                <VFileInput
                                                    :rules="bankData.gstin_image ? [fileSizeValidator] : [requiredValidator, fileSizeValidator]"
                                                    variant="plain"
                                                    :class="`file-input  ${bankData.gstin_image ? 'success-input' : ''}`"
                                                    name="gstin_image" :accept="'.jpeg,.png,.jpg,.webp'" :show-size="1024"
                                                    @change="uploadImage" />
                                                <VTextField :rules="[requiredValidator]" v-model="bankData.gstin"
                                                    :label="$t('gst_no') + ' * '" />
                                            </div>
                                        </VCol>
                                        <VCol cols="12" md="4">
                                            <div class="d-flex position-relative">
                                                <VFileInput
                                                    :rules="bankData.cin_image ? [fileSizeValidator] : [requiredValidator, fileSizeValidator]"
                                                    variant="plain"
                                                    :class="`file-input  ${bankData.cin_image ? 'success-input' : ''}`"
                                                    name="cin_image" :accept="'.jpeg,.png,.jpg,.webp'" :show-size="1024"
                                                    @change="uploadImage" />
                                                <VTextField :rules="[requiredValidator]" v-model="bankData.cin"
                                                    :label="$t('cin') + ' * '" />
                                            </div>
                                        </VCol>
                                        <VCol cols="12" md="4">
                                            <div class="d-flex position-relative">
                                                <VFileInput
                                                    :rules="bankData.pan_image ? [fileSizeValidator] : [requiredValidator, fileSizeValidator]"
                                                    variant="plain"
                                                    :class="`file-input  ${bankData.pan_image ? 'success-input' : ''}`"
                                                    name="pan_image" :accept="'.jpeg,.png,.jpg,.webp'" :show-size="1024"
                                                    @change="uploadImage" />
                                                <VTextField :rules="[requiredValidator]" v-model="bankData.pan"
                                                    :label="$t('pan_no') + ' * '" />
                                            </div>
                                        </VCol>
                                    </VRow>
                                </VCardText>
                            </VCard>
                        </VCol>
                    </VRow>

                    <VRow>
                        <VCol cols="12 text-right">
                            <VBtn variant="outlined" color="secondary mr-3" :to="{
                                name: 'profile-tab-id',
                                params: { tab: 'billing', id: userId }
                            }">
                                {{ $t("can_btn") }}
                            </VBtn>
                            <VBtn class="mr-3" color="primary"
                                :to="{ name: 'edit-profile-tab-uid', params: { tab: 'profile-details', uid: userId } }">
                                {{ $t("previous") }}
                            </VBtn>
                            <VBtn type="submit" color="primary">
                            <ButtonLoader v-show="isSnackbarVisibileDialog"></ButtonLoader>
                            {{ $t("next") }}
                        </VBtn>
                    </VCol>
                </VRow>
            </VForm>


        </VCardText>
    </section>

</div></template>