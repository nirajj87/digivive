<script lang="ts" setup>
const route = useRoute();
    const userId = Number(route.params.id) ?? 0;
</script>

<template>
     <section>
        <VCardText class="pt-0">
            <VRow>
                <VCol cols="12" >
                    <h4 class="mb-2">{{ $t("image_settings") }}</h4>
                    <VCard border class="mb-5" >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <h5>
                                        <!-- <VIcon icon="tabler-brand-aws" /> -->
                                        {{ $t("aws") }}</h5>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("key") }}</h5>
                                    <p class="mb-0">aws key here</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("secret") }}</h5>
                                    <p class="mb-0">secret key here</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("region") }}</h5>
                                    <p class="mb-0">region here</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("default_acl") }}</h5>
                                    <p class="mb-0">Default acl here</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("bucket") }}</h5>
                                    <p class="mb-0">bucket here</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("path_format") }}</h5>
                                    <p class="mb-0">path format here</p>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>

                    <VCard border class="mb-5" >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <h5>
                                        {{ $t("cloudinary") }}</h5>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("key") }}</h5>
                                    <p class="mb-0">aws key here</p>
                                </VCol>
                                <VCol cols="6">
                                    <h5>{{ $t("secret") }}</h5>
                                    <p class="mb-0">secret key here</p>
                                </VCol>
                                <VCol cols="12">
                                    <h5>{{ $t("bucket") }}</h5>
                                    <p class="mb-0">bucket here</p>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>

                    <VRow>
                        <VCol cols="12 text-right">
                            <VBtn
                                prepend-icon="tabler-edit"
                                :to="{ name: 'advance-setting-tab-category-userid',
                    params: {tab:'edit',category:'image-settings',userid:userId} }"
                            >
                                {{$t('edit_image_setting')}}
                            </VBtn>
                        </VCol>
                    </VRow>
                </VCol>
            </VRow>
        </VCardText>
    </section>
</template>