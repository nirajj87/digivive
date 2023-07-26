<script lang="ts" setup>

</script>
<template>
    <section>
        <div class="mt-5">
            <h5>{{ $t("live") }}</h5>
            <VCard border>
                <VCardText>
                    <VRow>
                        <VCol cols="12" md="12">
                            <p class="mb-0">{{$t("streaming_cdn")}}</p>
                            <h5>Cloudfront</h5>
                        </VCol>
                        <VCol cols="12" md="12">
                            <p class="mb-0">{{$t("path_url")}}</p>
                            <h5>secret key</h5>
                        </VCol>
                    </VRow>
                </VCardText>
            </VCard>
        </div>
        <div class="mt-5">
            <h5>{{ $t("live_event") }}</h5>
            <VCard border>
                <VCardText>
                    
                    <VRow>
                        <VCol cols="12" md="12">
                            <p class="mb-0">{{$t("streaming_cdn")}}</p>
                            <h5>Cloudfront</h5>
                        </VCol>
                        <VCol cols="12" md="12">
                            <p class="mb-0">{{$t("path_url")}}</p>
                            <h5>secret key</h5>
                        </VCol>
                    </VRow>
                </VCardText>
            </VCard>
        </div>

        <VRow class="mt-3">
            <VCol cols="12 text-right">
                <VBtn
                    prepend-icon="tabler-edit"
                >
                {{$t('edit_live_setting')}}
            </VBtn>
        </VCol>
        </VRow>
    </section>
</template>