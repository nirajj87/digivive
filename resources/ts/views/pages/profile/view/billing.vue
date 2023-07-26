<script lang="ts" setup>


import { userBillingStore } from '@/views/pages/profile/billingStore'
// import type { UserParams } from './../types'
import type { Billing, UserParams } from './../types'
import checkimg from '@images/pages/cancelled_cheque.jpg';
import Clipboard from 'clipboard';

const billingStore = userBillingStore()
const billingData = ref<Billing>({

});
const route = useRoute();
const userId = Number(route.params.id) ?? 0;

const isLoading = ref(true);
const isSnackbarVisibileDialog = ref(false);
const snackbarClass = ref('')
const snackBarMessage = ref();
const showTooltip = ref(false);

// const base_url = window.location.origin + '/storage/app/public/';
const base_url = window.location.origin.replace(window.location.port, '5173') + '/storage/app/public/';

onBeforeMount(() => {

    billingStore.getBillingDetails(userId)
        .then((response: any) => {
            billingData.value = response.data.user_profile.billing;
            isLoading.value = false;
        })
        .catch((error) => {
            console.log('Internal Server Error : ', error);
            isSnackbarVisibileDialog.value = true;
            snackBarMessage.value = error.message;
            snackbarClass.value = 'delete-snackbar';
        })

})

const copyText = (text: string) => {


    const clipboard = new Clipboard('.copy-button', {
        text: () => text,
    });

    clipboard.on('success', () => {
        isSnackbarVisibileDialog.value = true;
        snackbarClass.value = 'success-snackbar';
        snackBarMessage.value = 'text_copy'
        setTimeout(() => {
            isSnackbarVisibileDialog.value = false;
        }, 2000);
        // console.log('Text copied to clipboard!');
        clipboard.destroy();
    });

    clipboard.on('error', (e) => {
        console.error('Failed to copy text:', e);
        isSnackbarVisibileDialog.value = true;
        snackbarClass.value = 'delete-snackbar';
        snackBarMessage.value = 'failed_to_copy'
        setTimeout(() => {
            isSnackbarVisibileDialog.value = false;
        }, 2000);
        clipboard.destroy();
    });



}

const downloadImage = (responseUrl: string, imagePath: any) => {

    // if image is not available
    if (!imagePath) {
        return;
    }

    //getting imageFile Name
    const imagePathArr = imagePath.split('/');
    const fileName = imagePathArr[imagePathArr.length - 1];

    fetch(responseUrl)
        .then((response) => response.blob())
        .then((blob) => {
            const blobUrl = URL.createObjectURL(blob);
            const link = document.createElement("a");
            link.href = blobUrl;
            link.setAttribute("download", fileName);
            document.body.appendChild(link);
            link.click();

            isSnackbarVisibileDialog.value = true;
            snackbarClass.value = 'success-snackbar';
            snackBarMessage.value = 'download-success'

            document.body.removeChild(link); // Remove the link after download
            URL.revokeObjectURL(blobUrl); // Release the blob URL
        })
        .catch((error) => {
            console.error("Error downloading image:", error);
            isSnackbarVisibileDialog.value = true;
            snackbarClass.value = 'delete-snackbar';
            snackBarMessage.value = 'download-failed'
        });

}

</script>

<template>
    <div>

        <section>

            <VSnackbar v-model="isSnackbarVisibileDialog" location="top end" :class="snackbarClass"> {{
                $t(snackBarMessage) }}
            </VSnackbar>

            <VCardText class="pt-0">
                <PageLoader v-show="isLoading"></PageLoader>
                <!-- <div v-show="isLoading" class="loading-logo">
                <PageLoader></PageLoader>
            </div> -->
                <VRow v-show="!isLoading">


                    <VCol cols="12">

                        <h4 class="mb-2">{{ $t("bank_detail") }}</h4>
                        <VCard border>
                            <VCardText>

                                <VRow>
                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("beneficiary_name") }}
                                        </p>

                                        <h5>{{ billingData.benificiary_name ? billingData.benificiary_name :
                                            $t('not_available') }}
                                            <VIcon v-show="billingData.benificiary_name" size="14" icon="tabler-copy"
                                                class="copy-button"
                                                @click="copyText(billingData.benificiary_name as string)" />
                                        </h5>

                                        <!-- <v-tooltip :value="showTooltip">
                                            <template #activator="{ on }">
                                                <h5>
                                                    {{ billingData.benificiary_name ? billingData.benificiary_name :
                                                        $t('not_available') }}
                                                    <VIcon v-show="billingData.benificiary_name" size="14"
                                                        icon="tabler-copy" class="copy-button"
                                                        @click="copyText(billingData.benificiary_name as string)"
                                                        v-on="on" />
                                                </h5>
                                            </template>
                                            <span>Text Copied!</span>
                                        </v-tooltip> -->

                                    </VCol>
                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("bank_name") }}

                                        </p>
                                        <h5>{{ billingData.bank_name ? billingData.bank_name : $t('not_available') }}
                                            <VIcon v-show="billingData.bank_name" size="14" icon="tabler-copy"
                                                class="copy-button" @click="copyText(billingData.bank_name as string)" />
                                        </h5>
                                    </VCol>
                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("account_no") }}

                                        </p>
                                        <h5>{{ billingData.account_number ? billingData.account_number :
                                            $t('not_available') }}
                                            <VIcon v-show="billingData.account_number" size="14" icon="tabler-copy"
                                                class="copy-button"
                                                @click="copyText(billingData.account_number as string)" />
                                        </h5>
                                    </VCol>
                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("ifsc_code") }}

                                        </p>
                                        <h5>{{ billingData.ifsc_code ? billingData.ifsc_code : $t('not_available') }}
                                            <VIcon v-show="billingData.ifsc_code" size="14" icon="tabler-copy"
                                                class="copy-button" @click="copyText(billingData.ifsc_code as string)" />
                                        </h5>
                                    </VCol>
                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("swift_code") }}

                                        </p>
                                        <h5>{{ billingData.swift_code ? billingData.swift_code : $t('not_available') }}
                                            <VIcon v-show="billingData.swift_code" size="14" icon="tabler-copy"
                                                class="copy-button" @click="copyText(billingData.swift_code as string)" />
                                        </h5>
                                    </VCol>

                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("bank_address") }}

                                        </p>
                                        <h5>{{ billingData.address ? billingData.address : $t('not_available') }}
                                            <VIcon v-show="billingData.address" size="14" icon="tabler-copy"
                                                class="copy-button" @click="copyText(billingData.address as string)" />
                                        </h5>
                                    </VCol>



                                </VRow>

                                <VRow>
                                    <VCol cols="12" md="4">
                                        <h4 class="mb-2">{{ $t("cancelled_cheque") }}
                                            <VIcon v-show="billingData.cheque" size="16" icon="tabler-file-download"
                                                @click="downloadImage((base_url + billingData.cheque), billingData.cheque)" />
                                        </h4>
                                        <VImg v-show="billingData.cheque" class="cheque_img mb-0"
                                            :src="base_url + billingData.cheque" />
                                        <h5 v-show="!billingData.cheque && billingData.cheque != ''" class="mb-2"> {{
                                            $t('not_available') }} </h5>
                                    </VCol>
                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>

                    <VCol cols="12">
                        <h4 class="mb-2">{{ $t("other_detail") }}</h4>
                        <VCard border>
                            <VCardText>
                                <VRow>
                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("gst_no") }}

                                            <VIcon size="16" icon="tabler-file-download"
                                                @click="downloadImage((base_url + billingData.gstin_image), billingData.gstin_image)" />
                                        </p>
                                        <h5>{{ billingData.gstin ? billingData.gstin : $t('not_available') }}
                                            <VIcon v-show="billingData.gstin" icon="tabler-copy" size="14"
                                                class="copy-button" @click="copyText(billingData.gstin as string)" />
                                        </h5>
                                        <!-- <VImg class="cheque_img mb-0" :src="billingData.gstin_image" /> -->
                                        <VImg class="cheque_img mb-0" :src="(base_url + billingData.gstin_image)" />

                                    </VCol>
                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("cin") }}

                                            <VIcon size="16" icon="tabler-file-download"
                                                @click="downloadImage((base_url + billingData.cin_image), billingData.cin_image)" />
                                        </p>
                                        <h5>{{ billingData.cin ? billingData.cin : $t('not_available') }}
                                            <VIcon v-show="billingData.cin" icon="tabler-copy" size="14" class="copy-button"
                                                @click="copyText(billingData.cin as string)" />
                                        </h5>
                                        <VImg class="cheque_img mb-0" :src="base_url + billingData.cin_image" />
                                    </VCol>
                                    <VCol cols="12" md="4">
                                        <p class="mb-0">{{ $t("pan_no") }}

                                            <VIcon size="16" icon="tabler-file-download"
                                                @click="downloadImage((base_url + billingData.pan_image), billingData.pan_image)" />
                                        </p>
                                        <h5>{{ billingData.pan ? billingData.pan : $t('not_available') }}
                                            <VIcon v-show="billingData.pan" icon="tabler-copy" size="14" class="copy-button"
                                                @click="copyText(billingData.pan as string)" />
                                        </h5>
                                        <VImg class="cheque_img mb-0" :src="base_url + billingData.pan_image" />
                                    </VCol>

                                </VRow>
                            </VCardText>
                        </VCard>
                    </VCol>

                    <VCol cols="12">
                        <VBtn prepend-icon="tabler-edit" :to="{
                            name: 'edit-profile-tab-uid',
                            params: { tab: 'billing', uid: userId }
                        }">
                            {{ $t('edit_billing_detail') }}
                        </VBtn>
                    </VCol>
                </VRow>
            </VCardText>
        </section>
    </div>
</template>

<style lang="scss" scoped>
.card-list {
    --v-card-list-gap: 5px;
}

.server-close-btn {
    inset-inline-end: 0.5rem;
}
</style>