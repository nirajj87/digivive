<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import DateEditable from '@/pages/masters/date-formats/view/DateEditable.vue';
import { useDateStore } from '@/pages/masters/date-formats/view/useDateStore';

// use store
const dateListStore = useDateStore()
const route = useRoute()

// set Params
const platformData = ref({
  title: '',
  format_key: '',
  order: ''
})

watchEffect(() => {
  let platformId = Number(route.params.id) ?? 0;

  if (platformId) {
    dateListStore.fetchDetails(platformId)
      .then((response: any) => {
        if (response.success) {
          platformData.value = response.data;
        }
      })
      .catch((e) => {
        console.error(e);
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
        <DateEditable :platform-data="platformData" :label-text="$t('edit')" :snack-bar="$t('edit_successfully')">
        </DateEditable>
      </VCol>
    </VCard>
  </div>
</template>
<route lang="yaml">
    meta:
        layout: default
        action: id_edit
        subject: date-formats
</route>
