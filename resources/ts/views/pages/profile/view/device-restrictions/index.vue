<script lang="ts" setup>

  const dprofile = ['144', '360', '720', '1080']
  const status = ['Active', 'Inactive']
  const presets = ['144', '320', '480', '720', '1080']
  const ios = ['Mobile', 'Tablet', 'Computer', 'Smart TV']

  const DeviceRestriction = ref(false)
  const VSD = ref(false)
  const VHD = ref(false)
  const ASD = ref(false)
  const AHD = ref(false)
  const route = useRoute();
  const userId = Number(route.params.id) ?? 0;
 
  const device_restriction = ref(false)
</script>

<template>
    <VCardText class="pt-0">
       
        <VRow>
            <VCol cols="12" >
                <h4 class="mb-2">{{ $t("device_restriction") }}</h4>
                <VCard border class="mb-5" >
                    <VCardText>
                        <VRow>
                            <VCol cols="12">
                                <div class="mb-0 d-flex align-center">
                                    <h6 class="text-base font-weight-semibold mr-3">{{ $t("device_restriction_enable") }}</h6>
                                        <VChip
                                            size="small"
                                            class="text-capitalize"
                                            color="success"
                                        >
                                        <VIcon size="18" icon="tabler-circle-check" />
                                            {{$t("active")}}
                                        </VChip>  
                                </div>   
                            </VCol>
                        </VRow>
                        <VRow>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("allow_device") }}</p>
                                <h5>4</h5>
                            </VCol>
                            <VCol cols="12" md="3">
                                <p class="mb-0">{{ $t("api_duration") }}</p>
                                <h5>44343S</h5>
                            </VCol>
                            <VCol cols="12" md="6">
                                <p class="mb-0">{{ $t("os") }}</p>
                                <h5>Web, Android, IOs</h5>
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>
   
        <VRow>
            <VCol cols="12" class="text-right">
                <VBtn
                    prepend-icon="tabler-edit"
                    :to="{ name: 'advance-setting-tab-category-userid',
                    params: {tab:'edit',category:'device-restriction',userid:userId} }"  >
                    {{$t('edit_device_restriction_setting')}}
                </VBtn>
            </VCol>
        </VRow>
    </VCardText>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 5px;
}

.server-close-btn {
  inset-inline-end: 0.5rem;
}
</style>