

<template>
     <VCol cols="12">
                    <h5 class="mb-2">{{ $t("sms_template") }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="12">
                                    <VTextField :label="$t('title') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextarea
                                        :label="$t('template') + ' * ' "
                                        clearable
                                        counter
                                    />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextarea
                                        :label="$t('variables') + ' * ' "
                                        clearable
                                        counter
                                    />
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
                        type="submit"
                        color="primary"
                    >
                        {{$t("sub_btn")}}
                    </VBtn>
                </VCol>
</template>