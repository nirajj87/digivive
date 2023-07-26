<script lang="ts" setup>

</script>
<template>
    <section>
        <VCardText class="pt-0">
            <h4 class="mb-2">{{ $t("drm_setting") }}</h4>
            <VCard border>
                <VCardText>
                    <VRow>
                        <VCol cols="12" md="6">
                            <p class="mb-0">{{$t("drm_name")}}</p>
                            <h5>Cloudfront</h5>
                        </VCol>
                        <VCol cols="12" md="6">
                            <p class="mb-0">{{$t("secret")}}</p>
                            <h5>Cloudfront</h5>
                        </VCol>
                        <VCol cols="12" md="12">
                            <p class="mb-0">{{$t("base_path")}}</p>
                            <h5>secret key</h5>
                        </VCol>
                    </VRow>
                </VCardText>
            </VCard>
            <VRow class="mt-3">
            <VCol cols="12 text-right">
                <VBtn
                    prepend-icon="tabler-edit"
                >
                {{$t('edit_drm_setting')}}
            </VBtn>
        </VCol>
        </VRow>
        </VCardText>
        

        
    </section>
</template>