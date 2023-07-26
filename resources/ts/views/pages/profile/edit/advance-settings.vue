<script setup lang="ts">
import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
import UserEditTranscoding from '@/views/pages/profile/edit/transcoding/index.vue';
import UserEditSmtp from '@/views/pages/profile/edit/smtp/index.vue';
import UserEditDeviceRestrictions from '@/views/pages/profile/edit/device-restrictions/index.vue';
import UserEditStorageSettings from '@/views/pages/profile/edit/storage-settings/index.vue';
import UserEditSmsSettings from '@/views/pages/profile/edit/sms-settings/index.vue';
import UserEditBillingSettings from '@/views/pages/profile/edit/billing-settings/index.vue';
import UserEditSearchSettings from '@/views/pages/profile/edit/search-settings/index.vue';
import UserEditImageSettings from '@/views/pages/profile/edit/image-settings/index.vue';
import UserEditEmailTemplateSettings from '@/views/pages/profile/edit/email-templates/index.vue';
import UserEditReportSettings from '@/views/pages/profile/edit/report/index.vue';
import UserStreamingSetting from '@/views/pages/profile/edit/streaming-setting/index.vue';
import UserDRMSetting from '@/views/pages/profile/edit/drm-setting/index.vue';
import UserSignupSetting from '@/views/pages/profile/edit/signup-setting/index.vue';

// 👉 Store
const userProfileStore = useUserProfileStore();

const route = useRoute()
const userData = ref()
const activeTab = ref(route.params.category);
const subTab = ref(route.params.sub_tab)
const subsubTab = ref(route.params.sub_sub_tab);
// alert(subsubTab.value);
const userId = Number(route.params.userid) ?? 0;




const tabs = [
    { title: 'transcoding_settings', icon: 'tabler-arrows-down-up', tab: 'advance-setting', category: 'profiles', },
    { title: 'storage_settings', icon: 'tabler-database',tab: 'advance-setting', category: 'storage-settings' },
    { title: 'image_settings', icon: 'tabler-photo',tab: 'advance-setting', category: 'image-settings' },
    { title: 'search_settings', icon: 'tabler-search',tab: 'advance-setting', category: 'search-settings' },
    { title: 'device_restriction', icon: 'tabler-ban',tab: 'advance-setting', category: 'device-restriction' },
    { title: 'smtp_setting', icon: 'tabler-mail-filled', tab: 'advance-setting', category: 'smtp-setting'},
    // { title: 'email_template', icon: 'tabler-mail',tab: 'advance-setting', category: 'email-templates' },
    { title: 'sms_setting', icon: 'tabler-inbox',tab: 'advance-setting', category: 'sms-settings' },
    { title: 'streaming_setting', icon: 'tabler-video',tab: 'advance-setting', category: 'streaming-setting',sub_tab:subTab.value?subTab.value : 'cdn',sub_sub_tab:subsubTab.value? subsubTab.value : 'streaming'},
    { title: 'drm_setting', icon: 'tabler-video',tab: 'advance-setting', category: 'drm-setting' },
    { title: 'signup_setting', icon: 'tabler-video',tab: 'advance-setting', category: 'signup-setting' },
    // { title: 'billing_settings', icon: 'tabler-credit-card',tab: 'billing_settings' },
    // { title: 'report', icon: 'tabler-ban',tab: 'report' },

]

userProfileStore.fetchUser(userId).then(response => {
  userData.value = response.data
})


</script>

<template>
    <section>
        <VRow>
            <VCol cols="12" md="3">
                <VTabs
                    v-model="activeTab"
                    direction="vertical"
                >
                    <VTab
                        v-for="item in tabs"
                        :key="item.icon"

                        :to="!item.sub_tab ? { name: 'advance-setting-tab-category-userid', params: {tab:'edit',category:item.category,userid:userId} } : { name: 'advance-setting-tab-category-userid-sub_tab-sub_sub_tab', params: {tab:'edit',category:item.category,userid:userId,sub_tab:item.sub_tab,sub_sub_tab:item.sub_sub_tab} }"
                        >
                        <VIcon
                            :size="18"
                            :icon="item.icon"
                            class="me-1"
                        />
                        {{$t(item.title) }}
                    </VTab>
                </VTabs>
            </VCol>
            <VCol cols="12" md="9">
                <VWindow
                    v-model="activeTab"
                    class="disable-tab-transition"
                    :touch="false"
                >
                    <VWindowItem>
                        <UserEditTranscoding />
                    </VWindowItem>
                    <VWindowItem >
                        <UserEditStorageSettings />
                    </VWindowItem>

                    <VWindowItem >
                        <UserEditImageSettings />
                    </VWindowItem>
                    <VWindowItem >
                        <UserEditSearchSettings />
                    </VWindowItem>

                    <VWindowItem >
                        <UserEditDeviceRestrictions />
                    </VWindowItem> 
                    <VWindowItem >
                        <UserEditSmtp />
                    </VWindowItem>
                   
                    <!-- <VWindowItem >
                        <UserEditEmailTemplateSettings />
                    </VWindowItem> -->

                    <VWindowItem >
                        <UserEditSmsSettings />
                    </VWindowItem>

                    <VWindowItem >
                        <UserStreamingSetting />
                    </VWindowItem>

                    <VWindowItem >
                        <UserDRMSetting />
                    </VWindowItem>

                    <VWindowItem >
                        <UserSignupSetting />
                    </VWindowItem>
                    
                    <VWindowItem >
                        <UserEditBillingSettings />
                    </VWindowItem>
                  
                    <VWindowItem >
                        <UserEditReportSettings />
                    </VWindowItem>

                </VWindow>

              
            </VCol>
        </VRow>
    </section>
</template>
