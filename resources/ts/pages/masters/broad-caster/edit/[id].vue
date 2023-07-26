<script lang="ts" setup>
import breadcrumbs from '@/pages/breadcrumbs/list/index.vue';
import BroadCasterEditable from '@/pages/masters/broad-caster/view/BroadCasterEditable.vue';
import { useBroadCasterStore } from '@/pages/masters/broad-caster/view/useBroadCasterStore';

// use platform store
const broadListStore = useBroadCasterStore()
const route = useRoute()

// set platform Params
const platformData = ref({
  title: '',
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
        <BroadCasterEditable :platform-data="platformData" :label-text="$t('edit')" :snack-bar="$t('edit_successfully')">
        </BroadCasterEditable>
      </VCol>
    </VCard>
  </div>
</template>

<route lang="yaml">
    meta:
        layout: default
        action: id_edit
        subject: broadcaster
</route>
