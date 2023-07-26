<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import ViewerRatingEditable from '@/pages/masters/viewer-rating/view/ViewerRatingEditable.vue';
import { useViewerRatingStore } from '@/pages/masters/viewer-rating/view/useViewerRatingStore';

// use platform store
const viewerListStore = useViewerRatingStore()
const route = useRoute()

// set platform Params
const platformData = ref({
  title: '',
})

watchEffect(() => {
  let platformId = Number(route.params.id) ?? 0;

  if (platformId) {
    viewerListStore.fetchDetails(platformId)
      .then((response: any) => {
        console.log('edit response====>', response.data);

        if (response.success) {
          platformData.value = response.data;
          console.log('edit successfully======>', platformData.value);

        }
      })
      .catch((e) => {

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
      <ViewerRatingEditable :platform-data="platformData" :label-text="$t('edit')" :snack-bar="$t('edit_successfully')">
      </ViewerRatingEditable>
    </VCol>
  </VCard>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: id_edit
        subject: platform
</route>
