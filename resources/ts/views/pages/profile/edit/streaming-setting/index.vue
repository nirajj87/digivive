<script setup lang="ts">

import Streaming from '@/views/pages/profile/edit/streaming-setting/streaming/index.vue';
import LiveStreaming from '@/views/pages/profile/edit/streaming-setting/live/index.vue';
import VodStreaming from '@/views/pages/profile/edit/streaming-setting/vod/index.vue';



// 👉 Store


const route = useRoute()
const userData = ref()
const activeTab = ref(route.params.tab);
const subTab = ref(route.params.sub_tab);
const userId = Number(route.params.userid) ?? 0;




const tabs = [
    { title: 'streaming_setting', icon: 'tabler-video', tab: 'streaming-setting', subtab:'cdn',sub_sub_tab:'streaming' },
    { title: 'live', icon: 'tabler-live-view',tab: 'streaming-setting', subtab:'cdn',sub_sub_tab:'live' },
    { title: 'vod', icon: 'tabler-movie',tab: 'streaming-setting', subtab:'cdn',sub_sub_tab:'vod'},
]



const currentTab = ref(0)
</script>

<template>
    <section>
        <VCardText class="pt-0">
            <VTabs
                v-model="activeTab"
            >
                <VTab
                    v-for="item in tabs"
                    :key="item.icon"
                    :to="{ name: 'advance-setting-tab-category-userid-sub_tab-sub_sub_tab', params: {tab:'edit',userid:userId,sub_tab:item.subtab,sub_sub_tab:item.sub_sub_tab} }"
                    >
                    <VIcon
                        :size="18"
                        :icon="item.icon"
                        class="me-1"
                    />
                    {{$t(item.title) }}
                </VTab>
            </VTabs>
            
            <VWindow
                v-model="activeTab"
                class="disable-tab-transition"
                :touch="false"
            >
                <VWindowItem>
                    <Streaming />
                </VWindowItem>

                <VWindowItem >
                    <LiveStreaming />
                </VWindowItem>

                <VWindowItem >
                    <VodStreaming />
                </VWindowItem>
            </VWindow>
        </VCardText>
    </section>
</template>
