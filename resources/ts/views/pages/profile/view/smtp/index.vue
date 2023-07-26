<script lang="ts" setup>
  const route = useRoute();
  const userId = Number(route.params.id) ?? 0;
  const dprofile = ['144', '360', '720', '1080']
  const status = ['Active', 'Inactive']
  const ios = ['Mobile', 'Tablet', 'Computer', 'Smart TV']

  const DeviceRestriction = ref(false)
</script>

<template>
    <VCardText class="pt-0">
       
        <VRow>
            <VCol cols="12" >
                <h4 class="mb-2">{{ $t("smtp_setting") }}</h4>
                <VCard border class="mb-5" >
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="3">
                                <h5>{{$t("host_name")}}</h5>
                                <p>digivive</p>
                            </VCol>
                            <VCol cols="12" md="3">
                                <h5>{{$t("user_name")}}</h5>
                                <p>root</p>
                            </VCol>
                            <VCol cols="12" md="3">
                                <h5>{{$t("password")}}</h5>
                                <p>abc</p>
                            </VCol>
                            <VCol cols="12" md="3">
                                <h5>{{$t("port")}}</h5>
                                <p>21</p>
                            </VCol>
                            <VCol cols="12" md="3">
                                <h5>{{$t("encryption")}}</h5>
                                <p>xyz</p>
                            </VCol>
                            <VCol cols="12" md="3">
                                <h5>{{$t("from_email")}}</h5>
                                <p>abc@xyz.com</p>
                            </VCol>
                            
                            <VCol cols="12" md="3">
                                <h5>{{$t("status")}}</h5>
                                <p>Active</p>
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
                    :to="{name:'advance-setting-tab-category-userid', params: {tab:'edit',category:'smtp-setting', userid: userId} }"  >
                    {{$t('edit_smtp_setting')}}
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