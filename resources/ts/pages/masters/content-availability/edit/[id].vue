<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import ContentEditable from '@/pages/masters/content-availability/view/ContentEditable.vue';
import { useContentStore } from '@/pages/masters/content-availability/view/useContentStore';

// use content store
const contentListStore = useContentStore()
const route = useRoute()

// set content Params
const contentData = ref({
  title: ''
})

watchEffect(() => {
  let contentId = Number(route.params.id) ?? 0;

  if (contentId) {
    contentListStore.fetchDetails(contentId)
      .then((response: any) => {
        console.log('edit response====>', response.data);

        if (response.success) {
          contentData.value = response.data;
          console.log('edit successfully======>', contentData.value);
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
        <ContentEditable :content-data="contentData" :label-text="$t('edit')" :snack-bar="$t('edit_successfully')">
        </ContentEditable>
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
