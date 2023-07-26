<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import PaymentEditable from '@/pages/masters/payment-mode/view/PaymentEditable.vue';
import { usePaymentStore } from '@/pages/masters/payment-mode/view/usePaymentStore';

// use store
const paymentListStore = usePaymentStore()
const route = useRoute()

// set Params
const paymentData = ref({
  title: '',
})

watchEffect(() => {
  let paymentId = Number(route.params.id) ?? 0;

  if (paymentId) {
    paymentListStore.fetchDetails(paymentId)
      .then((response: any) => {
        // console.log('edit response====>', response.data);
        if (response.success) {
          paymentData.value = response.data;
          // console.log('edit successfully======>', bitframeData.value);
        }
      })
      .catch((e) => {
        console.log("Edit Error=====>", e);
      })
  }
})

const breadcrumbsData = ref({
  page_name: 'edit_user',
  name: 'edit_user',
});

</script>

<template>
  <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

  <VCard>
    <VCol cols="12" md="12">
      <PaymentEditable :payment-data="paymentData" :label-text="$t('edit')" :snack-bar="$t('edit_successfully')">
      </PaymentEditable>
    </VCol>
  </VCard>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: id_edit
        subject: payment_mode
</route>
