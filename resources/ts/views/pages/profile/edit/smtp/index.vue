<script lang="ts" setup>
import InfoDrawer from '@/views/apps/modal/info-drawer.vue'
  const route = useRoute();
  const dprofile = ['144', '360', '720', '1080']
  const status = ['Active', 'Inactive']
  const presets = ['144', '320', '480', '720', '1080']
  const ios = ['Mobile', 'Tablet', 'Computer', 'Smart TV'];

  let infoData = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
  let infoTitle = 'What is Lorem Ipsum?';

  const DeviceRestriction = ref(false)
  const VSD = ref(false)
  const VHD = ref(false)
  const ASD = ref(false)
  const AHD = ref(false)
  

  const smtp_setting = ref(false)

    const userId = Number(route.params.uid) ?? 0;
    const smslist = ["SendGrid", "SMTP"]

    const isDialogVisible = ref(false)

    const isInfoDrawerVisible = ref(false)
</script>

<template>
    <section>
    <VCardText class="pt-0">
       
        <VRow >
            <VCol cols="12" >
                <h5 style="position: relative;" class="mb-2">{{ $t("smtp_setting") }}
                    <VIcon @click="isDialogVisible = true" size="40" icon="tabler-info-small" />
                    <VDialog
                        v-model="isDialogVisible"
                        class="v-dialog-sm"
                    >
                        <!-- Dialog close btn -->
                        <DialogCloseBtn @click="isDialogVisible = false" />
                        <VCard >
                            <div class="light_bg">
                                <VCardText>
				                    <h5>{{ $t("smtp_setting") }}</h5>
				                    <VBtn variant="text" color="secondary" class="popup_close" @click="onCancel"></VBtn>
			                    </VCardText>
                            </div>
                            <VCardText>
                                <h5>What is Lorem Ipsum?</h5>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            </VCardText>

                            <VCardText class="light_bg text-right">
                                <VBtn
                                    variant="tonal"
                                    color="secondary"
                                    @click="isDialogVisible = false"
                                    class="mt-4"
                                >
                                    Close
                                </VBtn>
                            </VCardText>
                        </VCard>
                    </VDialog>

                    <VIcon @click="isInfoDrawerVisible = true" size="40" icon="tabler-info-small" />
                    
                </h5>

                

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
     <!-- 👉 Info modal -->
     <InfoDrawer
      v-model:isDrawerOpen="isInfoDrawerVisible"
      :infoData="infoData"
      :infoTitle="infoTitle"
    />
</section>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 5px;
}

.server-close-btn {
  inset-inline-end: 0.5rem;
}
</style>