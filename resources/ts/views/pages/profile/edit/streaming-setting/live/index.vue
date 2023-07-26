<script setup lang="ts">


    const live = ref(false)
    const live_service = ref('')
    const live_event_service = ref('')
    const LiveEvent = ref(false);
    const cdn = localStorage.getItem('selected_cdn');
    const selected_cdn = cdn ? JSON.parse(cdn) : ['Cloudfront']
    console.log(cdn);
    
    const ServiceType = ref(selected_cdn);
  
    const route = useRoute()

    const userId = Number(route.params.userid) ?? 0;
   
</script>

<template>
    <section>
        <VRow class="mt-4">
            <VCol cols="12">
                <h5 class="mb-2">{{ $t("live") }}</h5>
                <VCard border >
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="6">
                                <VCheckbox
                                    class="checkbox-label-font"
                                    :label="$t('live')" 
                                    v-model="live"
                                    @change="live ? false : true"
                                />
                            </VCol>
                            <VCol cols="12" md="6">
                                <VCheckbox
                                    class="checkbox-label-font"
                                    :label="$t('live_event')" 
                                    v-model="LiveEvent"
                                    @change="LiveEvent ? false : wowza"
                                />
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
            </VCol>

            <VCol cols="12"  v-if="live">
                <h5 class="mb-2">{{ $t("live") }}</h5>
                <VCard border >
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="12">
                                <VSelect 
                                v-model="live_service"
                                    :items="ServiceType"
                                    :label="$t('select')"
                                />
                            </VCol>
                            <VCol cols="12" md="12" v-if="live_service" >
                                <VTextField :label="$t('path_url')" />
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
            </VCol>

            <VCol cols="12"  v-if="LiveEvent">
                <h5 class="mb-2">{{ $t("live_event") }}</h5>
                <VCard border >
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="12">
                                <VSelect 
                                v-model="live_event_service"
                                    :items="ServiceType"
                                    :label="$t('select')"
                                />
                            </VCol>
                            <VCol cols="12" md="12" v-if="live_event_service">
                                <VTextField :label="$t('path_url')" />
                            </VCol>
                        </VRow>
                    </VCardText>
                </VCard>
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
                    {{$t("save")}}
                </VBtn>
            </VCol>
        </VRow>
    </section>
</template>