<script lang="ts" setup>
  const route = useRoute();
  const dprofile = ['144', '360', '720', '1080']
  const status = ['Active', 'Inactive']
  const presets = ['144', '320', '480', '720', '1080']
  const ios = ['Mobile', 'Tablet', 'Computer', 'Smart TV']

  const DeviceRestriction = ref(false)
  const VSD = ref(false)
  const VHD = ref(false)
  const ASD = ref(false)
  const AHD = ref(false)
 
  const device_restriction = ref(false)

    const userId = Number(route.params.uid) ?? 0;
</script>

<template>
    <VCardText class="pt-0">
       
        <VRow>
            <VCol cols="12" >
                <h5 class="mb-2">{{ $t("device_restriction") }}</h5>
                <VCard border>
                    <VCardText>
                        <VRow>
                            <VCol cols="12">
                                <div class="mb-0 d-flex align-center">
                                    <h6 class="text-base font-weight-semibold mr-3">{{ $t("device_restriction_enable") }}</h6>
                                    <VSwitch title="Two-steps verification" inset v-model="DeviceRestriction" />   
                                </div>
                                <VRow v-if="DeviceRestriction">
                                    <VCol cols="12" md="3">
                                        <VTextField :label="$t('allow_device')" type="number" />
                                    </VCol>
                                    <VCol cols="12" md="3">
                                        <VTextField :label="$t('api_duration')" type="number" />
                                    </VCol>
                                    <VCol cols="12" md="6">
                                        <v-select
                                            :items="ios"
                                            :menu-props="{ maxHeight: '400' }"
                                            :label="$t('os')"
                                            multiple
                                        >
                                            <template v-slot:selection="{ item, index }">
                                                <v-chip v-if="index < 2">
                                                    <span>{{ item.title }}</span>
                                                </v-chip>
                                                <span
                                                    v-if="index === 2"
                                                    class="text-grey text-caption align-self-center"
                                                >
                                                    (+{{ ios.length - 2 }} others)
                                                </span>
                                            </template>
                                        </v-select>
                                    </VCol>
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
                                {{$t("sub_btn")}}
                                </VBtn>
                                    </VCol>
                                </VRow>
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
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