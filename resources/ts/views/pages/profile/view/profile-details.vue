<script lang="ts" setup>

import avatar1 from '@images/avatars/a1.jpg'
import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
import type { UserParams } from './../types'

const userData = ref<UserParams>()
const managerList = ref<UserParams>()

const userProfileStore = useUserProfileStore()

const route = useRoute()

const isLoading = ref(true)

const userId = Number(route.params.id) ?? 0;

// 👉 Fetching user
const fetchUser = () => {
    userProfileStore.fetchUser(userId).then(response => {
        isLoading.value = true;
        if (response.data.success) {
            userData.value = response.data.data;
        }
    })
}

// 👉 Fetching manager
const fetchManager = () => {
    userProfileStore.fetchUserManagerList().then(response => {
        isLoading.value = true;
        if (response.data.success) {
            managerList.value = response.data.data;
        }
    }).catch(error => {
        console.error(error)
    })
}

watchEffect(() => {
    fetchUser()
    fetchManager()
})

</script>

<template>
    <div>
        <VCardText class="pt-0">

            <!-- USER INFORMATION -->
            <VRow>
                <VCol cols="12">
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="1">
                                    <!-- 👉 Avatar -->
                                    <VAvatar class="border-upload avatar-name" size="70"
                                        :image="userData?.profile_img ? userData?.profile_img : ''">
                                        <span>{{ userData?.first_name ? userData?.first_name?.slice(0, 2) : '' }}</span>
                                    </VAvatar>
                                </VCol>
                                <VCol cols="12" md="11">

                                    <h6 class="text-h6 text-sm-start font-weight-semibold">{{ userData?.role_id == 2 ?
                                        userData?.company_name : '' }}
                                        <VChip size="small" class="text-capitalize"
                                            :color="userData?.status == 1 ? 'success' : 'error'">
                                            <VIcon size="18"
                                                :icon="userData?.status == 1 ? 'tabler-circle-check' : 'tabler-user-x'" />
                                            {{ userData?.status == 1 ? $t("active") : $t("inactive") }}
                                        </VChip>
                                    </h6>

                                    <p class="mb-0">{{ $t("timezone") }} : {{ userData?.timezone ? userData?.timezone : 'NA'
                                    }} {{ $t("country") }} : {{ userData?.usercountry?.title ? userData?.usercountry?.title : 'NA' }}
                                    </p>


                                    <VList class="d-flex light-text">
                                        <VListItemTitle>
                                            {{ $t("name") }} : <b>{{ userData?.first_name }} {{ userData?.last_name }}</b>
                                        </VListItemTitle>
                                        <VListItemTitle>
                                            {{ $t("email") }} : <b>{{ userData?.email ? userData?.email : 'NA' }}</b>
                                        </VListItemTitle>
                                        <VListItemTitle>
                                            {{ $t("phone_number") }} : <b>{{ userData?.phone_number ? userData?.phone_number
                                                :
                                                'NA' }}</b>
                                        </VListItemTitle>
                                        <VListItemTitle>
                                            {{ $t("designation") }} : <b>{{ userData?.designation ? userData?.designation :
                                                'NA'
                                            }}</b>
                                        </VListItemTitle>
                                    </VList>

                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>

            <!-- COMMUNICATION INFORMTION -->
            <VRow>
                <VCol cols="12">
                    <h4 class="mb-2">{{ $t("communication_channel") }}</h4>
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" class="d-flex light-text">
                                    <label class="d-flex align-center mr-10">
                                        <VIcon icon="tabler-mail-filled" color="error" size="20" />
                                        {{ $t("email") }} : {{ userData?.user_profile.communication_channel.email ?
                                            userData?.user_profile.communication_channel.email : 'abhishek@gmail.com' }}
                                    </label>
                                    <label class="d-flex align-center ml-10">
                                        <VIcon icon="tabler-phone-filled" color="error" size="20" />
                                        {{ $t("phone") }} : {{ userData?.user_profile.communication_channel.phone_number ?
                                            userData?.user_profile.communication_channel.phone_number : 'NA' }}
                                    </label>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>

            <!-- ADDRESS INFORMATION -->
            <VRow>
                <VCol cols="12">
                    <h4 class="mb-2">{{ $t("address_information") }}</h4>
                    <VCard border>
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("address_line1") }}</p>
                                    <h5>{{ userData?.user_profile?.address.line1 ? userData?.user_profile?.address.line1 :
                                        'NA'
                                    }}</h5>
                                </VCol>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("address_line2") }}</p>
                                    <h5>{{ userData?.user_profile?.address.line2 ? userData?.user_profile?.address.line2 :
                                        'NA'
                                    }}</h5>
                                </VCol>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("landmark") }}</p>
                                    <h5>{{ userData?.user_profile?.address.landmark ?
                                        userData?.user_profile?.address.landmark :
                                        'NA' }}</h5>
                                </VCol>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("country") }}</p>
                                    <h5>{{
                                        userData?.user_profile?.address?.country?.title ?
                                        userData?.user_profile?.address?.country?.title : 'NA' }}</h5>
                                </VCol>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("state") }}</p>
                                    <h5>
                                        {{ userData?.user_profile?.address?.state?.title ?
                                            userData?.user_profile?.address?.state?.title : 'NA' }}</h5>
                                </VCol>
                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("city") }}</p>
                                    <h5>{{
                                        userData?.user_profile?.address?.city?.title ?
                                        userData?.user_profile?.address?.city?.title :
                                        'NA'
                                    }}
                                    </h5>
                                </VCol>

                                <VCol cols="12" md="3">
                                    <p class="mb-0">{{ $t("postal_zip_code") }}</p>
                                    <h5>{{ userData?.user_profile?.address.zip_code ?
                                        userData?.user_profile?.address.zip_code :
                                        'NA' }}</h5>
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>

            <!-- MANAGER INFORMATION -->
            <VRow>
                <VCol cols="12">
                    <VExpansionPanels>
                        <VExpansionPanel>
                            <VExpansionPanelTitle>
                                <h4 class="mb-2">{{ $t("your_manager") }}</h4>
                            </VExpansionPanelTitle>
                            <VExpansionPanelText>
                                <VCard border class="mb-3">
                                    <VCardText>
                                        <VRow v-for="manager in managerList" :key="manager.id">
                                            <VCol cols="12" class="mb-2">
                                                <VRow>
                                                    <VCol cols="12" md="1">
                                                        <!-- 👉 Avatar -->
                                                        <VAvatar class="border-upload avatar-name" size="60"
                                                            :image="manager?.profile_img ? 'http://localhost:8000/storage/' + manager?.profile_img : avatar1" />
                                                    </VCol>
                                                    <VCol cols="12" md="11">
                                                        <div class="d-flex flex-column justify-center">
                                                            <h5>{{ manager?.name }}
                                                                <VChip size="small" class="text-capitalize" color="success">
                                                                    <VIcon size="18" icon="tabler-circle-check" />
                                                                    {{ $t("active") }}
                                                                </VChip>
                                                            </h5>
                                                            <VList class="light-text d-flex pt-0">
                                                                <VListItemTitle>
                                                                    {{ $t("email") }} : <b>{{ manager?.email ?
                                                                        manager?.email : 'NA' }}</b>
                                                                </VListItemTitle>
                                                                <VListItemTitle>
                                                                    {{ $t("phone_number") }} : <b>{{ manager?.phone_number ?
                                                                        manager?.phone_number : 'NA' }}</b>
                                                                </VListItemTitle>
                                                            </VList>
                                                        </div>
                                                    </VCol>

                                                </VRow>
                                            </VCol>
                                        </VRow>

                                        <!-- <VRow>
                                        <VCol cols="12" md="6" class="mb-2">
                                            <VRow>
                                                <VCol cols="12" md="2">
                                                   
                                                    <VAvatar class="border-upload avatar-name" size="60"
                                                        :image="userData?.profile_img ? 'http://localhost:8000/storage/' + userData?.profile_img : avatar1" />
                                                </VCol>
                                                <VCol cols="12" md="10">
                                                    <div class="d-flex flex-column justify-center">
                                                        <h5>Manager Name (designation)
                                                            <VChip size="small" class="text-capitalize" color="success">
                                                                <VIcon size="18" icon="tabler-circle-check" />
                                                                {{ $t("active") }}
                                                            </VChip>
                                                        </h5>
                                                        <VList class="light-text pt-0">
                                                            <VListItemTitle>
                                                                {{ $t("email") }} : <b>kashif.azad@digivive.com</b>
                                                            </VListItemTitle>
                                                            <VListItemTitle>
                                                                {{ $t("phone_number") }} : <b>9717384217</b>
                                                            </VListItemTitle>
                                                        </VList>
                                                    </div>
                                                </VCol>
                                            </VRow>
                                        </VCol>

                                        <VCol cols="12" md="6" class="mb-2">
                                            <VRow class="border-left">
                                                <VCol cols="12" md="2">
                                                  
                                                    <VAvatar class="border-upload avatar-name" size="60"
                                                        :image="userData?.profile_img ? 'http://localhost:8000/storage/' + userData?.profile_img : avatar1" />
                                                </VCol>
                                                <VCol cols="12" md="10">
                                                    <div class="d-flex flex-column justify-center">
                                                        <h5>Manager Name (designation )</h5>
                                                        <VList class="light-text pt-0">
                                                            <VListItemTitle>
                                                                {{ $t("email") }} : <b>kashif.azad@digivive.com</b>
                                                            </VListItemTitle>
                                                            <VListItemTitle>
                                                                {{ $t("phone_number") }} : <b>9717384217</b>
                                                            </VListItemTitle>
                                                        </VList>
                                                    </div>
                                                </VCol>
                                            </VRow>
                                        </VCol>

                                        <VCol cols="12" md="6" class="mb-2">
                                            <VRow>
                                                <VCol cols="12" md="2">
                                                  
                                                    <VAvatar class="border-upload avatar-name" size="60"
                                                        :image="userData?.profile_img ? 'http://localhost:8000/storage/' + userData?.profile_img : avatar1" />
                                                </VCol>
                                                <VCol cols="12" md="10">
                                                    <div class="d-flex flex-column justify-center">
                                                        <h5>Manager Name (designation )</h5>
                                                        <VList class="light-text pt-0">
                                                            <VListItemTitle>
                                                                {{ $t("email") }} : <b>kashif.azad@digivive.com</b>
                                                            </VListItemTitle>
                                                            <VListItemTitle>
                                                                {{ $t("phone_number") }} : <b>9717384217</b>
                                                            </VListItemTitle>
                                                        </VList>
                                                    </div>
                                                </VCol>
                                            </VRow>
                                        </VCol>

                                        <VCol cols="12" md="6" class="mb-2">
                                            <VRow class="border-left">
                                                <VCol cols="12" md="2">
                                            
                                                    <VAvatar class="border-upload avatar-name" size="60"
                                                        :image="userData?.profile_img ? 'http://localhost:8000/storage/' + userData?.profile_img : avatar1" />
                                                </VCol>
                                                <VCol cols="12" md="10">
                                                    <div class="d-flex flex-column justify-center">
                                                        <h5>Kashif azad (designation)</h5>
                                                        <VList class="light-text pt-0">
                                                            <VListItemTitle>
                                                                {{ $t("email") }} : <b>kashif.azad@digivive.com</b>
                                                            </VListItemTitle>
                                                            <VListItemTitle>
                                                                {{ $t("phone_number") }} : <b>9717384217</b>
                                                            </VListItemTitle>
                                                        </VList>
                                                    </div>
                                                </VCol>
                                            </VRow> 
                                        </VCol>
                                    </VRow>  -->

                                    </VCardText>
                                </VCard>
                            </VExpansionPanelText>
                        </VExpansionPanel>
                    </VExpansionPanels>
                </VCol>
            </VRow>
 
            <!-- EDIT PROFILE BUTTON -->
            <VRow>
                <VCol cols="12">
                    <VBtn prepend-icon="tabler-edit" :to="{
                        name: 'edit-profile-tab-uid',
                        params: { tab: 'profile-details', uid: userId }
                    }">
                        {{ $t('edit_profile_detail') }}
                    </VBtn>
                </VCol>
            </VRow>
            
        </VCardText>
    </div>
</template>