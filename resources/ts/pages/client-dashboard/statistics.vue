<script setup lang="ts">
const statistics = [
    {
        title: 'Total User',
        stats: '230k',
        icon: 'tabler-users',
        color: 'primary',
    },
    {
        title: '30 Days Users',
        stats: '8549',
        icon: 'tabler-users',
        color: 'primary',
    },
    {
        title: 'Total Revenue',
        stats: '1.423k',
        icon: 'tabler-currency-rupee',
        color: 'success',
    },
    {
        title: '30 Days Revenue',
        stats: '9745',
        icon: 'tabler-currency-rupee',
        color: 'success',
    },
]
</script>
<template>
    <section>
        <!-- <VCard title="Statistics">
        <template #append>
        <span class="text-body-2">Updated 1 month ago</span>
        </template> -->
        <VCard>
            <VCardText>
                <VRow>
                    <VCol v-for="item in statistics" :key="item.title" cols="12" md="3">
                        <div class="d-flex">
                            <VAvatar :color="item.color" variant="tonal" size="42" class="me-3">
                                <VIcon size="24" :icon="item.icon" />
                            </VAvatar>
                            <div class="d-flex flex-column">
                                <span class="text-h6 font-weight-medium">{{ item.stats }}</span>
                                <span class="text-caption">{{ item.title }}</span>
                            </div>
                        </div>
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>