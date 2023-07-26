<script lang="ts" setup>
    const aws = ref(true)
    const cloudinary = ref(false)
    const route = useRoute()
    const userId = Number(route.params.uid) ?? 0;
    const StorageItems = ["AWS" , "Cloudinary"]
</script>
<template>
    <section>
        <VCardText class="pt-0">
            <VRow>
                <VCol cols="12">
                    <h5 class="mb-2">{{ $t("image_settings") }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <VAutocomplete
                                        :items="StorageItems"
                                        :label="$t('select') + ' *'"
                                        multiple
                                    />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>

                <VCol cols="12">
                    <h5 class="mb-2">{{ $t("aws") }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <VCheckbox
                                        class="checkbox-label-font"
                                        :label="$t('aws_same')"
                                    />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('key') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('secret') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('region') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('default_acl') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('bucket') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('path_format') + ' * ' " />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>

                <VCol cols="12">
                    <h5 class="mb-2">{{ $t("cloudinary") }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VFileInput :label="$t('key') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('secret') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="12">
                                    <VTextField :label="$t('bucket') + ' * ' " />
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