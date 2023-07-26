<script setup lang="ts">
interface Props {
    successMsg: string
  isDialogVisible: boolean
}

interface Emit {
  (e: 'update:isDialogVisible', value: boolean): void
  (e: 'confirm', value: boolean): void
}

const props = defineProps<Props>()

const emit = defineEmits<Emit>()

const updateModelValue = (val: boolean) => {
  emit('update:isDialogVisible', val)
}

const onConfirmation = () => {
  emit('confirm', true)
  updateModelValue(false)
}

const onCancel = () => {
  emit('confirm', false)
  emit('update:isDialogVisible', false)
}
</script>

<template>
  <!-- 👉 Confirm Dialog -->
  <VDialog
    max-width="500"
    :model-value="props.isDialogVisible"
    @update:model-value="updateModelValue"
  >
    <VCard class="text-center px-10 py-6">
      <VCardText>
        <VBtn
          icon
          variant="outlined"
          color="success"
          class="mb-4"
          style="width: 88px; height: 88px; pointer-events: none;"
        >
          <span class="text-5xl">✓</span>
        </VBtn>

        <h6 class="text-sm font-weight-large">
          {{ props.successMsg }}
        </h6>
      </VCardText>

      <VCardActions class="align-center justify-center gap-2">
        <VBtn
        color="success"
          variant="elevated"
          @click="onConfirmation"
        >
          Ok
        </VBtn>

        <!-- <VBtn
          color="secondary"
          variant="tonal"
          @click="onCancel"
        >
          Cancel
        </VBtn> -->
      </VCardActions>
    </VCard>
  </VDialog>
</template>
