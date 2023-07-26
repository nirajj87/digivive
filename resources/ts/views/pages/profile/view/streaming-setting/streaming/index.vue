<script lang="ts" setup>

</script>
<template>
    <section class="mt-5">
        <VCard border>
            <VCardText>
                <VRow>
                    <VCol cols="12" md="6">
                        <p class="mb-0">{{$t("streaming_cdn")}}</p>
                        <h5>Cloudfront</h5>
                    </VCol>
                    <VCol cols="12" md="6">
                        <p class="mb-0">{{$t("secret")}}</p>
                        <h5>secret key</h5>
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
                {{$t('edit_streaming')}}
            </VBtn>
        </VCol>
        </VRow>
    </section>
</template>