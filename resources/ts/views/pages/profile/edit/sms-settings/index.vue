<script lang="ts" setup>
  const route = useRoute();
  const status = ['Active', 'Inactive']





    const userId = Number(route.params.uid) ?? 0;
    const smslist = ["NexGtv Platform", "AWS SNS"]

    const isDialogVisible = ref(false)
</script>

<template>
    <VCardText class="pt-0">
       
        <VRow >
            <VCol cols="12" >
                <h5 style="position: relative;" class="mb-2">{{ $t("sms_setting") }}</h5>
                <VCard border >
                    <VCardText>
                        <VRow>
                            <VCol cols="12">
                                <VSelect
                                    :items="smslist"
                                    :label="$t('select')"
                                />
                            </VCol>
                            <VCol cols="12">
                                <VTextField :label="$t('host_name')" />
                            </VCol>
                            <VCol cols="12" md="6">
                                <VTextField :label="$t('user_name')" />
                            </VCol>
                            <VCol cols="12" md="6">
                                <VTextField :label="$t('password')"
                                type="password"
                                />
                            </VCol>
                            <VCol cols="12" md="6">
                                <VTextField :label="$t('port')" />
                            </VCol>
                            <VCol cols="12" md="6">
                                <VTextField :label="$t('encryption')" />
                            </VCol>
                            <VCol cols="12" md="6">
                                <VTextField :label="$t('from_email')" />
                            </VCol>
                            <VCol cols="12" md="6">
                                <VSelect
                                    :items="status"
                                    :label="$t('status')"
                                />
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>

        <VRow>
            <VCol cols="12" class="text-right">
                <VBtn
            
            variant="outlined"
            color="secondary mr-3"
        >
          {{$t("can_btn")}}
        </VBtn>
        <VBtn
                    color="primary mr-3"
                    :to="{name:'edit-profile-tab-uid', params: {tab:'permissions', uid: userId} }"

                >
                    {{$t("previous")}}
                </VBtn>
        <VBtn
          type="submit"
          color="primary"
        >
          {{$t("save")}}
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