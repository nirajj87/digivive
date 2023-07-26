<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import BitframeEditable from '@/pages/masters/bitframe/view/BitframeEditable.vue';
import { useBitframeStore } from '@/pages/masters/bitframe/view/useBitframeStore';

// use store
const bitframeListStore = useBitframeStore()
const route = useRoute()

// set Params
const bitframeData = ref({
  type: '',
})

watchEffect(() => {
  let platformId = Number(route.params.id) ?? 0;

  if (platformId) {
    bitframeListStore.fetchDetails(platformId)
      .then((response: any) => {
        // console.log('edit response====>', response.data);
        if (response.success) {
          bitframeData.value = response.data;
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
      <BitframeEditable :bitframe-data="bitframeData" :label-text="$t('edit')" :snack-bar="$t('edit_successfully')">
      </BitframeEditable>
    </VCol>
  </VCard>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: id_edit
        subject: bitframe
</route>
