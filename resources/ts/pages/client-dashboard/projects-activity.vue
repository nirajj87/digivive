<script setup lang="ts">
const activeProjects = [
    {
        avatarImg: 'tabler-movie',
        title: 'Movie',
        stats: '65',
        progressColor: 'error',
    },
    {
        avatarImg: 'tabler-movie',
        title: 'Series',
        stats: '86',
        progressColor: 'primary',
    },
    {
        avatarImg: 'tabler-video',
        title: 'VOD',
        stats: '90',
        progressColor: 'success',
    },
    {
        avatarImg: 'tabler-headset',
        title: 'Padcast',
        stats: '37',
        progressColor: 'info',
    },
    {
        avatarImg: 'tabler-headphones',
        title: 'Audio',
        stats: '22',
        progressColor: 'primary',
    },
    {
        avatarImg: 'tabler-live-view',
        title: 'Live Channel',
        stats: '29',
        progressColor: 'warning',
    },
]
</script>

<template>
    <section>
        <VCard title="Projects Activity">
            <VCardText>
                <VList class="card-list">
                    <VListItem v-for="project in activeProjects" :key="project.title">
                        <template #prepend>
                            <VIcon size="18" :icon="project.avatarImg" />
                        </template>

                        <VListItemTitle class="font-weight-semibold">
                            {{ project.title }}
                        </VListItemTitle>

                        <template #append>
                            <div class="d-flex align-center">
                                <div class="me-2" style="width: 4.875rem;">
                                    <VProgressLinear :model-value="project.stats" :color="project.progressColor" height="8"
                                        rounded-bar rounded />
                                </div>
                                <span class="text-disabled">{{ project.stats }}%</span>
                            </div>
                        </template>
                    </VListItem>
                </VList>
            </VCardText>
        </VCard>
    </section>
</template>