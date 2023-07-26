<script lang="ts" setup>
import type { ViewerRatingList } from '@/pages/masters/viewer-rating/view/types';
import { useViewerRatingStore } from '@/pages/masters/viewer-rating/view/useViewerRatingStore';
import router from "@/router";
import { VForm } from 'vuetify/components';
import { requiredValidator } from '@validators';

// platform data store
const viewerListStore = useViewerRatingStore();

const route = useRoute();

// set props for platform
interface Props {
  platformData: ViewerRatingList,
  labelText: string,
  snackBar: string
}

const props = defineProps<Props>();

const errors = ref<Record<string, string | undefined>>({
  title: undefined,
  status: undefined
})

// set form data
const refForm = ref<VForm>();

const message = ref("");
const snackbarClass = ref('');

const isFormValid = ref(false);
const isSnackbarVisibileDialog = ref(false);


const items = [
  { title: 'Active', value: '1' },
  { title: 'Inactive', value: '0' },
]

// Form Submit
const onSubmit = () => {
  let platformId = Number(route.params.id) ?? 0;

  if (platformId) {
    props.platformData.status= props.platformData.status
    viewerListStore.update(props.platformData)
      .then((response: any) => {
        isSnackbarVisibileDialog.value = true
        snackbarClass.value = 'success-snackbar'
        message.value = response.data.message;

        setTimeout(() => {
          router.push({
            name: "masters-viewer-rating"
          });
        }, 2000);

      }).catch((e) => {
        if (e.response.data.message) {
          message.value = e.response.data.message
          console.log('error message.value===>', message.value);
        }
        const { errors: formErrors } = e.response.data;
        errors.value = formErrors;
      })
  }
  else {
    props.platformData.status= props.platformData.status 
    // console.log("Done==========>",props.platformData.status);
     
    viewerListStore.add(props.platformData)
      .then((response: any) => {
        isSnackbarVisibileDialog.value = true
        snackbarClass.value = 'success-snackbar'
        message.value = response.data.message;

        setTimeout(() => {
          router.push({
            name: "masters-viewer-rating"
          });
        }, 2000);

      }).catch((e) => {
        if (e.response.data.message) {
          message.value = e.response.data.message
        }
        const { errors: formErrors } = e.response.data;
        errors.value = formErrors;
      })
  }
}

</script>

<template>
  <section>
    <h5 class="mb-4">{{ props.labelText }}</h5>
    <VSnackbar :class='snackbarClass' v-model="isSnackbarVisibileDialog" location="top right"> {{
      props.snackBar }} </VSnackbar>
    <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
      <VRow>
        <VCol cols="12">
          <VCard border>
            <VCardText>
              <VRow>
                <VCol cols="12" md="4">
                  <VTextField :label="$t('item')"  :rules="[requiredValidator]" v-model="platformData.title" />
                </VCol>
                <VCol cols="12" md="4">
                  <VTextField :label="$t('order')"  :rules="[requiredValidator]" v-model="platformData.view_order" />
                </VCol>
                <VCol cols="12" md="4">
                  <VSelect :rules="[requiredValidator]" :items="items" v-model="platformData.status"
                    :label="$t('status')" />
                </VCol>
              </VRow>
            </VCardText>
          </VCard>
        </VCol>
      </VRow>
      <VRow>
        <VCol cols="12" class="text-right">
          <VBtn type="submit" color="secondary mr-3" :to="{ name: 'masters-viewer-rating' }">
            {{ $t("can_btn") }}
          </VBtn>
          <VBtn type="submit" @click="refForm?.validate()">
            <ButtonLoader v-show="isSnackbarVisibileDialog"></ButtonLoader>
            {{ $t("sub_btn") }}
          </VBtn>
        </VCol>
      </VRow>
    </VForm>
  </section>
</template>

<style lang="scss">
.form-border {
  border: 1px solid;
  border-color: rgba(var(--v-border-color), var(--v-border-opacity));
  padding: 15px;
  border-radius: 6px;
}
</style>