<script lang="ts" setup>
import { json } from 'stream/consumers';

    const route = useRoute()
    const userId = Number(route.params.uid) ?? 0;
    const toggleSwitch1 = ref(true);
    const toggleSwitch2 = ref(false);
    const toggleSwitch3 = ref(false);
    const toggleSwitch4 = ref(false);
    const toggleSwitch5 = ref(false);
    const toggleSwitch6 = ref(false);
    const toggleSwitch7 = ref(false);
</script>
<template>
    <section>
        <VCardText class="pt-0">
            <VRow>
                <VCol cols="12">
                    <h5 class="mb-2">{{ $t("signup_setting") }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="3">
                                    <VSwitch
                                        class="small-switch ml-2"
                                        v-model="toggleSwitch1"
                                        label="Name"
                                    />
                                </VCol>
                                <VCol cols="12" md="3">
                                    <VSwitch
                                        class="small-switch ml-2"
                                        v-model="toggleSwitch2"
                                        label="Email"
                                    />
                                </VCol>
                                <VCol cols="12" md="3">
                                    <VSwitch
                                        class="small-switch ml-2"
                                        v-model="toggleSwitch3"
                                        label="Phone"
                                    />
                                </VCol>
                                <VCol cols="12" md="3">
                                    <VSwitch
                                        class="small-switch ml-2"
                                        v-model="toggleSwitch4"
                                        label="DOB"
                                    />
                                </VCol>
                                <VCol cols="12" md="3">
                                    <VSwitch
                                        class="small-switch ml-2"
                                        v-model="toggleSwitch5"
                                        label="Address"
                                    />
                                </VCol>
                                <VCol cols="12" md="3">
                                    <VSwitch
                                        class="small-switch ml-2"
                                        v-model="toggleSwitch6"
                                        label="Gender"
                                    />
                                </VCol>
                                <VCol cols="12" md="3">
                                    <VSwitch
                                        class="small-switch ml-2"
                                        v-model="toggleSwitch7"
                                        label="Occupation"
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