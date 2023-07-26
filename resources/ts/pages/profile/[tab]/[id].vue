<script setup lang="ts">
import { useUserProfileStore } from '@/views/pages/profile/useUserProfileStore'
import UserProfile from '@/views/pages/profile/view/profile-details.vue'
import Billing from '@/views/pages/profile/view/billing.vue'
import Security from '@/views/pages/profile/view/security.vue'
import Permissions from '@/views/pages/profile/view/permissions.vue'
import AdvanceSetting from '@/views/pages/profile/view/advance-settings.vue'
import  breadcrumbs  from '@/pages/breadcrumbs/list/index.vue'
// 👉 Store
const userProfileStore = useUserProfileStore()

const route = useRoute()
const userData = ref()
const activeTab = ref(route.params.tab);
const userId = Number(route.params.id) ?? 0;

const tabs = [
    { title: 'details', icon: 'tabler-user-check', tab: 'profile-details' },
    { title: 'billing', icon: 'tabler-credit-card', tab: 'billing' },
    { title: 'security', icon: 'tabler-settings',tab: 'security' },
    { title: 'permissions', icon: 'tabler-circle-key',tab: 'permissions'},
    { title: 'advance_setting', icon: 'tabler-settings', tab: 'advance_setting' }  
]

userProfileStore.fetchUser(Number(route.params.id)).then(response => {
    userData.value = response.data
})

let breadcrumbsData = ref<Record<string, string | undefined>>({
    page_name: 'profile_details',
    name: 'profile_details',
});
</script>

<template>
    <section>
        <breadcrumbs :breadcrumbs-data="breadcrumbsData" />

        <VCard v-if="userData">
            <VCardText>
                <VTabs
                    v-model="activeTab"
                >
                    <VTab
                        v-for="item in tabs"
                        :key="item.icon"
                        :to="{ name: 'profile-tab-id', params: {tab: item.tab,id:userId} }"
                        @click="breadcrumbsData.name = item.title;breadcrumbsData.page_name = item.title"
                    >
                        <VIcon
                            :size="18"
                            :icon="item.icon"
                            class="me-1"
                        />
                        <span>{{$t(item.title) }}</span>
                    </VTab>
                </VTabs>
            </VCardText>

            <VWindow
                v-model="activeTab"
                class="disable-tab-transition"
                :touch="false"
            >

            <!-- Profile -->
            <VWindowItem >
                <UserProfile  />
            </VWindowItem>

            <!-- Billing -->
            <VWindowItem >
                <Billing  />
            </VWindowItem>

            <!-- Security -->
            <VWindowItem >
                <Security />
            </VWindowItem>

            <!-- permissions -->
            <VWindowItem >
                <Permissions />
            </VWindowItem> 

            <!-- Advance Setting -->
            <VWindowItem >
                <AdvanceSetting />
            </VWindowItem>
            </VWindow>

        </VCard>
    </section>
</template>
