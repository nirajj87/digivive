<script lang="ts" setup>
  const route = useRoute();
  const userId = Number(route.params.uid) ?? 0;
  const dprofile = ['144p', '360p', '720p', '1080p']
  const status = ['Active', 'Inactive']
  const presets = ['144', '320', '480', '720', '1080']
  const ios = ['Mobile', 'Tablet', 'Computer', 'Smart TV']

  const DeviceRestriction = ref(false)
  const VSD = ref(false)
  const VHD = ref(false)
  const ASD = ref(false)
  const AHD = ref(false)
  const transcoding = ref(false)




</script>

<template>
    <VCardText class="pt-0">
        <VRow>
            <VCol cols="12">
                <h5 class="mb-3">{{ $t("transcoding_settings") }}</h5>
                <VCard border >
                    <VCardText>
                        <h5 class="mb-3">{{ $t("video_transcoding") }}</h5>
                        <div class="d-flex align-center">
                            <h5 class="mr-2">{{$t("presets")}} * :</h5>
                            <div class="demo-space-x">
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="144p"
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="360p"
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="480p"
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="720p"
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="1080p"
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="Audio"
                                />
                            </div>
                        </div>
                        
                        <div class="d-flex mt-2">
                            <h5 class="mr-2 mt-2">{{ $t("download_profile") }} * :</h5>
                            <div class="d-flex">
                                <div class="demo-space-x">
                                    <VCheckbox
                                        class="checkbox-label-font"
                                        :label="$t('sd')" 
                                        true-value="1"
                                        false-value="0" 
                                        v-model="VSD"
                                    />

                                    <VSelect
                                        v-if="VSD"
                                        :items="dprofile"
                                        :label="$t('sd')"
                                        class="mmin-w100"
                                    />
                                </div>

                                <div class="demo-space-x">
                                    <VCheckbox
                                        class="checkbox-label-font"
                                        :label="$t('hd')" 
                                        true-value="1"
                                        false-value="0" 
                                        v-model="VHD"
                                    />
                                    <VSelect
                                        v-if="VHD"
                                        :items="dprofile"
                                        :label="$t('hd')"
                                        class="mmin-w100"
                                    />
                                </div>
                                <div class="demo-space-x">
                                    <VCheckbox
                                        class="checkbox-label-font"
                                        :label="$t('hq')" 
                                    />
                                </div>
                            </div>
                        </div>
                    </VCardText>
                </VCard>

                <VCard border class="mt-4" >
                    <VCardText>
                        <h5 class="mb-3">{{ $t("audio_transcoding") }}</h5>
                        <div class="d-flex align-center">
                            <h5 class="mr-2">{{$t("presets")}} * :</h5>
                            <div class="demo-space-x">
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="144p" 
                                    true-value="1"
                                    false-value="0" 
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="360p" 
                                    true-value="1"
                                    false-value="0" 
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="480p" 
                                    true-value="1"
                                    false-value="0" 
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="720p"
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="1080p"
                                />
                                <VCheckbox
                                    class="checkbox-label-font"
                                    label="Audio"
                                />
                            </div>
                        </div>
                        <div  class="d-flex mt-2">
                            <h5 class="mr-2 mt-2">{{ $t("download_profile") }} * :</h5>
                            <div class="d-flex">
                                <div class="demo-space-x">
                                    <VCheckbox
                                        class="checkbox-label-font"
                                        :label="$t('lq')"                                         
                                    />
                                </div>
                            
                                <div class="demo-space-x">
                                    <VCheckbox
                                        class="checkbox-label-font"
                                        :label="$t('hq')" 
                                    />
                                </div>
                            </div>
                        </div>
                    </VCardText>
                    
                </VCard>
            </VCol>

            <VCol cols="12" class="text-right pt-0">
                <VBtn
                    variant="outlined"
                    color="secondary mr-3"
                    :to="{name: 'profile-tab-id',params: {tab:'advance-setting', id: userId}}"
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