<script lang="ts" setup>
import { json } from 'stream/consumers';

    const route = useRoute()
    const userId = Number(route.params.uid) ?? 0;
    const streamoption = ["Digivive encryption", "Pallycon", "Vdocipher", "Verimatrix "]
    const typeselect = ["Video", "Live"];
    const selected_cdn =ref(['Digivive encryption']);
    localStorage.removeItem('selected_cdn')
    const handleCdnChange = (cdn: string) => {
        localStorage.setItem('selected_cdn',JSON.stringify(cdn))
        // selected_cdn.value.push(cdn)
    }
</script>
<template>
    <section>
        <VCardText class="pt-0">
            <VRow>
                <VCol cols="12">
                    <h5 class="mb-2">{{ $t("drm_setting") }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="12">
                                    <VAutocomplete 
                                    v-model="selected_cdn"
                                        :items="streamoption"
                                        :label="$t('select_drm') + ' *'"
                                        @update:model-value="handleCdnChange"
                                        multiple
                                    />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>

                <VCol cols="12" v-for="cdn in selected_cdn">
                    <h5 class="mb-2">{{ $t(cdn) }}</h5>
                    <VCard border>
                        <VCardText>
                            <h5 class="mb-4">{{$t("widevine_android")}}</h5>
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('key') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('secret') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="12">
                                    <VTextField :label="$t('base_path') + ' * ' " />
                                </VCol>
                            </VRow>
                        </VCardText>
                        
                        <VCardText>
                            <VDivider class="mb-4" />
                            <h5 class="mb-4">{{$t("fairplay_ios")}}</h5>
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('key') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('secret') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="12">
                                    <VTextField :label="$t('base_path') + ' * ' " />
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
        </VCardText>
    </section>
</template>