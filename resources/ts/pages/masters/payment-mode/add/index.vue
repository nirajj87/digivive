<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import PaymentEditable from '@/pages/masters/payment-mode/view/PaymentEditable.vue';

// set Params
const paymentData = ref({
    title: ''
})

const breadcrumbsData = ref({
    page_name: 'add_client',
    name: 'add_client',
});

</script>

<template>
    <breadcrumbs :breadcrumbs-data="breadcrumbsData" />
    <VCard>
        <VCol cols="12" md="12">
            <PaymentEditable :payment-data="paymentData" :label-text="$t('add')" :snack-bar="$t('add_successfully')">
            </PaymentEditable>
        </VCol>
    </VCard>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: is_add
        subject: payment_mode
</route>