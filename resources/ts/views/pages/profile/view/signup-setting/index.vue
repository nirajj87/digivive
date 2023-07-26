<script lang="ts" setup>

</script>
<template>
    <section>
        <VCardText class="pt-0">
            <h4 class="mb-2">{{ $t("signup_setting") }}</h4>
            <VCard border>
                <VCardText>
                    <VRow>
                        <VCol cols="12" md="3">
                            <VChip
                                color="success"
                            >	   
                                <span>{{$t("name")}}</span>
                                    <VIcon
                                        icon="tabler-check"
                                        size="18"
                                    />
                            </VChip>
                        </VCol>
                        <VCol cols="12" md="3">
                            <VChip
                                color="success"
                            >	   
                                <span>{{$t("email")}}</span>
                                    <VIcon
                                        icon="tabler-check"
                                        size="18"
                                    />
                            </VChip>
                        </VCol>
                        <VCol cols="12" md="3">
                            <VChip
                                color="error"
                            >	   
                                <span>{{$t("phone")}}</span>
                                    <VIcon
                                        icon="tabler-x"
                                        size="18"
                                    />
                            </VChip>
                        </VCol>
                        <VCol cols="12" md="3">
                            <VChip
                                color="error"
                            >	   
                                <span>{{$t("dob")}}</span>
                                    <VIcon
                                        icon="tabler-x"
                                        size="18"
                                    />
                            </VChip>
                        </VCol>
                        <VCol cols="12" md="3">
                            <VChip
                                color="error"
                            >	   
                                <span>{{$t("address")}}</span>
                                    <VIcon
                                        icon="tabler-x"
                                        size="18"
                                    />
                            </VChip>
                        </VCol>
                        <VCol cols="12" md="3">
                            <VChip
                                color="error"
                            >	   
                                <span>{{$t("gender")}}</span>
                                    <VIcon
                                        icon="tabler-x"
                                        size="18"
                                    />
                            </VChip>
                        </VCol>
                        <VCol cols="12" md="3">
                            <VChip
                                color="error"
                            >	   
                                <span>{{$t("occupation")}}</span>
                                    <VIcon
                                        icon="tabler-x"
                                        size="18"
                                    />
                            </VChip>
                        </VCol>
                    </VRow>
                </VCardText>
            </VCard>

            <VRow class="mt-3">
            <VCol cols="12 text-right">
                <VBtn
                    prepend-icon="tabler-edit"
                >
                {{$t('edit_signup_setting')}}
            </VBtn>
        </VCol>
        </VRow>
        </VCardText>
    </section>
</template>