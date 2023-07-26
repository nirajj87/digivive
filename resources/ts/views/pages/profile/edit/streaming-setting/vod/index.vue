<script lang="ts" setup>
  const cdn = localStorage.getItem('selected_cdn');
    const selected_cdn = cdn ? JSON.parse(cdn) : ['Cloudfront']
    const ServiceType =ref(selected_cdn);
    const vod_cdn =ref('');
    const route = useRoute()

const userId = Number(route.params.userid) ?? 0;
</script>
<template>
    <section>
        <VRow class="mt-4">
            <VCol cols="12">
                <h5 class="mb-2">{{ $t("vod") }}</h5>
                <VCard border >
                    <VCardText>
                        <VRow>
                            <VCol cols="12" md="12">
                                <VSelect 
                                v-model="vod_cdn"
                                    :items="ServiceType"
                                    :label="$t('select')"
                                />
                            </VCol>

                            <VCol cols="12" md="12" v-if="vod_cdn" >
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