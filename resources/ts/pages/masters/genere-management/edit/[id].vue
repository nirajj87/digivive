<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import GenereEditable from '@/pages/masters/genere-management/view/GenereEditable.vue';
import { useGenereStore } from '@/pages/masters/genere-management/view/useGenereStore';

// use platform store
const broadListStore = useGenereStore()
const route = useRoute()

// set platform Params
const platformData = ref({
  title: '',
  user_id: ''
})


watchEffect(() => {
  let platformId = Number(route.params.id) ?? 0;

  if (platformId) {
    broadListStore.fetchDetails(platformId)
      .then((response: any) => {
        console.log('edit response====>', response.data);

        if (response.success) {
          platformData.value = response.data;
          console.log('edit successfully======>', platformData.value);

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
  <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

  <VCard>
    <VCol cols="12" md="12">
      <GenereEditable :platform-data="platformData" :label-text="$t('edit')" :snack-bar="$t('edit_successfully')">
      </GenereEditable>
    </VCol>
  </VCard>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: id_edit
        subject: platform
</route>
