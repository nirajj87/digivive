<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import AssetEditable from '@/pages/masters/asset-type/view/AssetEditable.vue';
import { useAssetStore } from '@/pages/masters/asset-type/view/useAssetStore';

// use content store
const contentListStore = useAssetStore()
const route = useRoute()

// set Params
const assetData = ref({
  title: ''
})

watchEffect(() => {
  let contentId = Number(route.params.id) ?? 0;

  if (contentId) {
    contentListStore.fetchDetails(contentId)
      .then((response: any) => {
        if (response.success) {
          assetData.value = response.data;
        }
      })
      .catch((e) => {
        console.log("Error====>", e);

      })
  }
})

const breadcrumbsData = ref({
  page_name: 'edit_user',
  name: 'edit_user',
});

</script>

<template>
  <div>
    <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

    <VCard>
      <VCol cols="12" md="12">
        <AssetEditable :asset-data="assetData" :label-text="$t('edit')" :snack-bar="$t('edit_successfully')">
        </AssetEditable>
      </VCol>
    </VCard>
  </div>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: id_edit
        subject: content
</route>
