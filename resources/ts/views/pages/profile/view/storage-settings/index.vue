<script lang="ts" setup>
const route = useRoute();
    const userId = Number(route.params.id) ?? 0;
</script>

<template>
    <section>
        <VCardText class="pt-0">
            <VRow>
                <VCol cols="12" >
                    <h4 class="mb-2">{{ $t("storage_settings") }}</h4>
                    <VCard border class="mb-5" >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <h5>
                                        <!-- <VIcon icon="tabler-brand-aws" /> -->
                                        {{ $t("aws") }}</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("key") }}</p>
                                    <h5>aws key here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("secret") }}</p>
                                    <h5>secret key here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("region") }}</p>
                                    <h5>region here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("default_acl") }}</p>
                                    <h5>Default acl here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("content_bucket") }}</p>
                                    <h5>content bucket here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("backup_bucket") }}</p>
                                    <h5>Backup bucket here</h5>
                                </VCol>
                                <VCol cols="12">
                                    <p class="mb-0">{{ $t("path_url") }}</p>
                                    <h5>path url here</h5>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>

                    <VCard border class="mb-5" >
                        <VCardText>
                            <VRow>
                                <VCol cols="12">
                                    <h5>{{ $t("wowza") }}</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("key") }}</p>
                                    <h5>aws key here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("secret") }}</p>
                                    <h5>secret key here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("region") }}</p>
                                    <h5>region here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("default_acl") }}</p>
                                    <h5>Default acl here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("content_bucket") }}</p>
                                    <h5>content bucket here</h5>
                                </VCol>
                                <VCol cols="6">
                                    <p class="mb-0">{{ $t("backup_bucket") }}</p>
                                    <h5>Backup bucket here</h5>
                                </VCol>
                                <VCol cols="12">
                                    <p class="mb-0">{{ $t("path_url") }}</p>
                                    <h5>path url here</h5>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>

                    <VRow>
                        <VCol cols="12 text-right">
                            <VBtn
                                prepend-icon="tabler-edit"
                                :to="{ name: 'advance-setting-tab-category-userid',
                    params: {tab:'edit',category:'storage-settings',userid:userId} }"
                            >
                                {{$t('edit_storage_setting')}}
                            </VBtn>
                        </VCol>
                    </VRow>
                </VCol>
            </VRow>
        </VCardText>
    </section>
</template>

<style lang="scss" scoped>

</style>