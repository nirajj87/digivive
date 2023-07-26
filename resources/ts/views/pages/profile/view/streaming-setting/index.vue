<script setup lang="ts">

import Streaming from '@/views/pages/profile/view/streaming-setting/streaming/index.vue';
import LiveStreaming from '@/views/pages/profile/view/streaming-setting/live/index.vue';
import VodStreaming from '@/views/pages/profile/view/streaming-setting/vod/index.vue';



// 👉 Store
const route = useRoute()
const userData = ref()
const activeTab = ref(route.params.tab);
const subTab = ref(route.params.sub_tab);
const userId = Number(route.params.userid) ?? 0;




const tabs = [
    { title: 'streaming_setting', icon: 'tabler-video', tab: 'streaming-setting' },
    { title: 'live', icon: 'tabler-live-view',tab: 'streaming-setting' },
    { title: 'vod', icon: 'tabler-movie',tab: 'streaming-setting'},
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
