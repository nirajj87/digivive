<script lang="ts" setup>
const isDialogVisible = ref(false)
const isSnackbarVisibleDialog = ref(false)
const isLoadingBtn = ref(false);


interface Emit {
  (e: 'aButttonClick'): void
}

const emit = defineEmits<Emit>()

const handle = () => {
  emit('aButttonClick')
  isLoadingBtn.value = true
  isDialogVisible.value = false
  isSnackbarVisibleDialog.value = true;

}
</script>
<template>
  <VBtn icon variant="text" color="default" size="x-small" @click="isDialogVisible = true">
    <VIcon :size="22" icon="tabler-trash" />
  </VBtn>
  <!-- Dialog -->
  <VDialog v-model="isDialogVisible" class="v-dialog-sm">
    <!-- Dialog close btn -->
    <VBtn icon class="v-dialog-close-btn" @click="isDialogVisible = !isDialogVisible">
      <VIcon icon="tabler-x" />
    </VBtn>
    <VCard>
      <VCardTitle>
        <VIcon color="error" icon="tabler-alert-triangle" size="25" /> Alert
      </VCardTitle>
      <VDivider/>
      <VCardText>
        Are you sure you want to delete this data ?</VCardText>
      <VCardText class="d-flex gap-4">
        <VBtn variant=tonal @click="isDialogVisible = false">
          No
        </VBtn>
        <VBtn @click="handle" :loading="isLoadingBtn" color="primary">
          Yes
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>