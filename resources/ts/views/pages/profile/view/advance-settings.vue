<script setup lang="ts">
import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
import Profiles from '@/views/pages/profile/view/transcode-profiles/index.vue';
import StorageSettings from '@/views/pages/profile/view/storage-settings/index.vue';
import ImageSettings from '@/views/pages/profile/view/image-settings/index.vue';
import SearchSettings from '@/views/pages/profile/view/search-settings/index.vue';
import UserSmtp from '@/views/pages/profile/view/smtp/index.vue';
import UserDeviceRestrictions from '@/views/pages/profile/view/device-restrictions/index.vue';
import EmailTemplates from '@/views/pages/profile/view/email-templates/index.vue';
import SMSSettings from '@/views/pages/profile/view/sms-settings/index.vue';
import BillingSettings from '@/views/pages/profile/view/billing-settings/index.vue';
import Report from '@/views/pages/profile/view/report/index.vue';
import StreamingSetting from '@/views/pages/profile/view/streaming-setting/index.vue';
import DRMSetting from '@/views/pages/profile/view/drm-setting/index.vue';
import SignupSetting from '@/views/pages/profile/view/signup-setting/index.vue';


// 👉 Store
const userProfileStore = useUserProfileStore()

const route = useRoute()
const userData = ref()
const activeTab = ref('');
const userId = Number(route.params.id) ?? 0;



const tabs = [
	{ title: 'transcoding_settings', icon: 'tabler-arrows-down-up', tab: 'transcode-profiles' },
	{ title: 'storage_settings', icon: 'tabler-database', tab: 'storage-settings' },
	{ title: 'image_settings', icon: 'tabler-photo', tab: 'image-settings' },
	{ title: 'search_settings', icon: 'tabler-search', tab: 'search-settings' },
	{ title: 'device_restriction', icon: 'tabler-ban',tab: 'device-restriction' },
  	{ title: 'smtp_setting', icon: 'tabler-mail-filled', tab: 'smtp-setting'},
	// { title: 'email_template', icon: 'tabler-mail', tab: 'email-templates' },
	// { title: 'sms_settings', icon: 'tabler-inbox', tab: 'sms-settings' },
	{ title: 'streaming_setting', icon: 'tabler-inbox', tab: 'streaming-setting' },
	{ title: 'drm_setting', icon: 'tabler-inbox', tab: 'drm-setting' },
	{ title: 'signup_setting', icon: 'tabler-inbox', tab: 'signup-setting' },
	// { title: 'billing_settings', icon: 'tabler-credit-card', tab: 'billing-settings' },
	// { title: 'report', icon: 'tabler-arrows-down-up', tab: 'report' },
]

userProfileStore.fetchUser(Number(route.params.id)).then(response => {
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
						:value="item.tab"
					>
						<VIcon
							:size="18"
							:icon="item.icon"
							class="me-1"
						/>
						<span>{{$t(item.title) }}</span>
					</VTab>
      			</VTabs>
      		</VCol>
      		<VCol cols="12" md="9">
				<VWindow
					v-model="activeTab"
					class="disable-tab-transition"
					:touch="true"
				>
					<VWindowItem value="transcode-profiles">
						<Profiles />
					</VWindowItem>

					<VWindowItem value="storage-settings">
						<StorageSettings />
					</VWindowItem>
					
					<VWindowItem value="image-settings">
						<ImageSettings />
					</VWindowItem>
					
					<VWindowItem value="search-settings">
						<SearchSettings />
					</VWindowItem>
					
					<VWindowItem value="smtp-setting">
						<UserSmtp />
					</VWindowItem>

					<VWindowItem value="device-restriction">
						<UserDeviceRestrictions />
					</VWindowItem>

					<VWindowItem value="email-templates">
						<EmailTemplates />
					</VWindowItem>
					
					<VWindowItem value="sms-settings">
						<SMSSettings />
					</VWindowItem>	
					<VWindowItem value="streaming-setting">
						<StreamingSetting />
					</VWindowItem>

					<VWindowItem value="drm-setting">
						<DRMSetting />
					</VWindowItem>

					<VWindowItem value="signup-setting">
						<SignupSetting />
					</VWindowItem>

					<VWindowItem value="billing-settings">
						<BillingSettings />
					</VWindowItem>

					<VWindowItem value="report">
						<Report />
					</VWindowItem>


				</VWindow>
      		</VCol>
    	</VRow>
	</section>
</template>
