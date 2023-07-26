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
                <h4 class="mb-2">{{ $t("transcoding_settings") }}</h4>
                <VCard border class="mb-5" >
                    <VCardText>
                        <VRow>
                            <VCol cols="12">
                                <h5>{{ $t("video_transcoding") }}</h5>
                            </VCol>
                        </VRow>
                        <VRow>
                            
                            <VCol cols="12">
                                <!-- <h5>{{$t("video_profile")}}</h5> -->
                                <div class="d-flex align-center profile_label">
                                        <h5 class="mr-2">{{$t("presets")}} :</h5>
                                        <div class="demo-space-x">
                                            <label>
                                                <VIcon
                                                size="18"
                                                icon='tabler-square-check-filled'
                                                color="secondary"
                                            />
                                            144p
                                            </label>
                                            <label>
                                                <VIcon
                                                size="18"
                                                icon='tabler-square-check-filled'
                                                color="secondary"
                                            />
                                            360p
                                            </label>
                                            <label>
                                                <VIcon
                                                size="18"
                                                icon='tabler-square-check-filled'
                                                color="secondary"
                                            />
                                            480p
                                            </label>
                                            <label>
                                                <VIcon
                                                size="18"
                                                icon='tabler-square-check-filled'
                                                color="secondary"
                                            />
                                            710p
                                            </label>
                                            <label class="disabled-color">
                                                <VIcon
                                                size="18"
                                                icon='tabler-square'
                                            />
                                            1080p
                                            </label>
                                        </div>
                                    </div>
                            </VCol>
                            
                            <VCol cols="12">
                                <h5>{{$t("download_profile")}}</h5>
                                <p class="mb-0">{{$t("sd")}} : 144 | {{$t("hd")}} : 720 | {{$t("hq")}}</p>
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
                <VCard border class="mb-5" >
                    <VCardText>
                        <VRow>
                            <VCol cols="12">
                                <h5>{{ $t("audio_transcoding") }}</h5>
                            </VCol>
                        </VRow>
                        <VRow>
                            <VCol cols="12">
                                <div class="d-flex align-center">
                                <h5 class="mr-2">{{$t("presets")}} :</h5>
                                <div class="demo-space-x profile_label">
                                            <label>
                                                <VIcon
                                                size="20"
                                                icon='tabler-square-check-filled'
                                                color="secondary"
                                            />
                                            144p
                                            </label>
                                            <label>
                                                <VIcon
                                                size="20"
                                                icon='tabler-square-check-filled'
                                                color="secondary"
                                            />
                                            360p
                                            </label>
                                            <label>
                                                <VIcon
                                                size="20"
                                                icon='tabler-square-check-filled'
                                                color="secondary"
                                            />
                                            480p
                                            </label>
                                            <label>
                                                <VIcon
                                                size="20"
                                                icon='tabler-square-check-filled'
                                                color="secondary"
                                            />
                                            710p
                                            </label>
                                            <label class="disabled-color">
                                                <VIcon
                                                size="20"
                                                icon='tabler-square'
                                            />
                                            1080p
                                            </label>
                                        </div>
                                </div>
                            </VCol>
                            
                            <VCol cols="12" md="3">
                                <h5>{{$t("download_profile")}}</h5>
                                <p class="mb-0">{{$t("lq")}} | {{$t("hq")}}</p>
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>
      

        
        <VRow>
            <VCol cols="12 text-right">
                <VBtn
                    prepend-icon="tabler-edit"
                    :to="{ name: 'advance-setting-tab-category-userid',
                    params: {tab:'edit',category:'profiles',userid:userId} }"  >
                    {{$t('edit_transcoding_setting')}}
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