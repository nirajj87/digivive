
<script setup lang="ts">
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'



interface Emit {
  (e: 'update:isDrawerOpen', value: boolean): void
}


interface Props {
  isDrawerOpen: boolean,
  infoData:any,
  infoTitle:any,
}

const props = defineProps<Props>();
    const emit = defineEmits<Emit>()





// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
}



const handleDrawerModelValueUpdate = (val: boolean) => {
  emit('update:isDrawerOpen', val)
}
</script>
<template>
    <VNavigationDrawer
      temporary
      :width="400"
      location="end"
      class="scrollable-content"
      :model-value="props.isDrawerOpen"
      @update:model-value="handleDrawerModelValueUpdate"
    >
      <!-- 👉 Title -->
      <div class="d-flex align-center pa-6 pb-1">
        <h6 class="text-h6">
          SMTP Info
        </h6>
        <VSpacer />

        <!-- 👉 Close btn -->
        <VBtn
        variant="tonal"
        color="default"
        icon
        size="32"
        class="rounded"
        @click="closeNavigationDrawer"
        >
        <VIcon
            size="18"
            icon="tabler-x"
        />
        </VBTn>
        </div>
        <PerfectScrollbar :options="{ wheelPropagation: false }">
            <VCard flat>
                <VCardText>
                                <h5>{{props.infoTitle}}</h5>
                                <p>{{ props.infoData }}</p>
                            </VCardText>
            </VCard>
        </PerfectScrollbar>
    </VNavigationDrawer>
</template>