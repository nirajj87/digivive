<script lang="ts" setup>
  const route = useRoute();
  const dprofile = ['144', '360', '720', '1080']
  const status = ['Active', 'Inactive']
  const ios = ['Mobile', 'Tablet', 'Computer', 'Smart TV']

  const DeviceRestriction = ref(false)
  const VSD = ref(false)
  const VHD = ref(false)
  const ASD = ref(false)
  const AHD = ref(false)
</script>

<template>
    <VCardText class="pt-0">
        <VRow>
            <VCol cols="12">
                <VExpansionPanels>
                    <VExpansionPanel>
                        <VExpansionPanelTitle>
                            <h4>{{ $t("transcoding") }}</h4>
                        </VExpansionPanelTitle>
                        <VExpansionPanelText>
                            <VCard border >
                                <VCardText>
                                
                                    <h5 class="mb-3">{{ $t("video_transcoding") }}</h5>
                                    <div class="d-flex align-center">
                                        <h5 class="mr-2">{{$t("profile")}} :</h5>
                                        <div class="demo-space-x">
                                            <VCheckbox
                                                class="checkbox-label-font"
                                                label="144" 
                                                true-value="1"
                                                false-value="0" 
                                            />
                                            <VCheckbox
                                                class="checkbox-label-font"
                                                label="360" 
                                                true-value="1"
                                                false-value="0" 
                                            />
                                            <VCheckbox
                                                class="checkbox-label-font"
                                                label="480" 
                                                true-value="1"
                                                false-value="0" 
                                            />
                                            <VCheckbox
                                                class="checkbox-label-font"
                                                label="720" 
                                                true-value="1"
                                                false-value="0" 
                                                disabled
                                            />
                                            <VCheckbox
                                                class="checkbox-label-font"
                                                label="1080" 
                                                true-value="1"
                                                false-value="0"
                                                disabled 
                                            />
                                            <VCheckbox
                                                class="checkbox-label-font"
                                                label="Audio" 
                                                true-value="1"
                                                false-value="0" 
                                                disabled
                                            />
                                        </div>
                                    </div>
                                    <div  class="d-flex">
                                        <h5 class="mr-2 mt-2">{{ $t("download_profile") }} :</h5>
                                        <VRow>
                                            <VCol cols="12" md="3">
                                                <div class="demo-space-x">
                                                    <VCheckbox
                                                        class="checkbox-label-font"
                                                        :label="$t('sd')" 
                                                        true-value="1"
                                                        false-value="0" 
                                                        v-model="VSD"
                                                    />
                                                    <VCheckbox
                                                        class="checkbox-label-font"
                                                        :label="$t('hd')" 
                                                        true-value="1"
                                                        false-value="0" 
                                                        v-model="VHD"
                                                    />
                                                   
                                                </div>
                                            </VCol>
                                          
                                        </VRow>
                                    </div>
                                
                                </VCardText>
                            </VCard>
                        <VCard border class="mt-4" >
                            <VCardText>
                                <h5 class="mb-3">{{ $t("audio_transcoding") }}</h5>
                                <div class="d-flex align-center">
                                    <h5 class="mr-2">{{$t("profile")}} :</h5>
                                    <div class="demo-space-x">
                                        <VCheckbox
                                            class="checkbox-label-font"
                                            label="144" 
                                            true-value="1"
                                            false-value="0" 
                                        />
                                        <VCheckbox
                                            class="checkbox-label-font"
                                            label="360" 
                                            true-value="1"
                                            false-value="0" 
                                        />
                                        <VCheckbox
                                            class="checkbox-label-font"
                                            label="480" 
                                            true-value="1"
                                            false-value="0" 
                                        />
                                        <VCheckbox
                                            class="checkbox-label-font"
                                            label="720" 
                                            true-value="1"
                                            false-value="0" 
                                            disabled
                                        />
                                        <VCheckbox
                                            class="checkbox-label-font"
                                            label="1080" 
                                            true-value="1"
                                            false-value="0"
                                            disabled 
                                        />
                                        <VCheckbox
                                            class="checkbox-label-font"
                                            label="Audio" 
                                            true-value="1"
                                            false-value="0" 
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div  class="d-flex">
                                    <h5 class="mr-2 mt-2">{{ $t("download_profile") }} :</h5>
                                    <VRow>
                                        <VCol cols="12" md="3">
                                            <div class="demo-space-x">
                                                <VCheckbox
                                                    class="checkbox-label-font"
                                                    :label="$t('sd')" 
                                                    true-value="1"
                                                    false-value="0" 
                                                    v-model="VSD"
                                                />
                                                <VCheckbox
                                                    class="checkbox-label-font"
                                                    :label="$t('hd')" 
                                                    true-value="1"
                                                    false-value="0" 
                                                    v-model="VHD"
                                                />
                                            </div>
                                        </VCol>
                                    </VRow>
                                </div>
                            </VCardText>
                        </VCard>
                        </VExpansionPanelText>
                    </VExpansionPanel>
                </VExpansionPanels>

            </VCol>


            <Cvol>
                <VBtn
                    @click="transcoding = false">
                    back
                </VBtn>
            </Cvol>
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