<script lang="ts" setup>
import type { ContentList } from '@/pages/masters/content-availability/view/types';
import { useContentStore } from '@/pages/masters/content-availability/view/useContentStore';
import router from "@/router";
import { VForm } from 'vuetify/components';
import { requiredValidator } from '@validators';

// content data store
const contentListStore = useContentStore();

const route = useRoute();

// set props
interface Props {
  contentData: ContentList
  labelText: string
  snackBar: string
}

const props = defineProps<Props>();

const errors = ref<Record<string, string | undefined>>({
  title: undefined,
})

// set form data
const refForm = ref<VForm>();

const items = [
  { title: 'Active', value: '1' },
  { title: 'Inactive', value: '0' },
]

const message = ref("");
const snackbarClass = ref('');

const isFormValid = ref(false);
const isSnackbarVisibileDialog = ref(false);

// Form Submit
const onSubmit = () => {

  let contentId = Number(route.params.id) ?? 0;

  if (contentId) {
    props.contentData.status = props.contentData.status
    contentListStore.update(props.contentData)
      .then((response: any) => {
        isSnackbarVisibileDialog.value = true
        snackbarClass.value = 'success-snackbar'
        message.value = response.data.message;
        console.log('message.value===>', message.value);
        setTimeout(() => {
          router.push({
            name: "masters-content-availability"
          });
        }, 1000);

      }).catch((e) => {
        if (e.response.data.message) {
          message.value = e.response.data.message
          console.log('Edit Failure===>', message.value);
        }
        const { errors: formErrors } = e.response.data;
        errors.value = formErrors;
      })
  }
  else {
    props.contentData.status = props.contentData.status
    contentListStore.add(props.contentData)
      .then((response: any) => {
        isSnackbarVisibileDialog.value = true;
        snackbarClass.value = 'success-snackbar'
        message.value = response.data.message;
        console.log("Message====>", message.value);

        setTimeout(() => {
          router.push({
            name: "masters-content-availability"
          });
        }, 1000);
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
  <div>
    <section>
      <VCardText>
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
                      <VTextField :label="$t('item')" :rules="[requiredValidator]" v-model="contentData.title" />
                    </VCol>
                    <VCol cols="12" md="4">
                      <VSelect :rules="[requiredValidator]" :items="items" v-model="contentData.status"
                        :label="$t('status')" />
                    </VCol>
                  </VRow>
                </VCardText>
              </VCard>
            </VCol>
          </VRow>
          <VRow>
            <VCol cols="12" class="text-right">
              <VBtn type="submit" color="secondary mr-3" :to="{ name: 'masters-content-availability' }">
                {{ $t("can_btn") }}
              </VBtn>
              <VBtn type="submit" @click="refForm?.validate()">
                <ButtonLoader v-show="isSnackbarVisibileDialog"></ButtonLoader>
                {{ $t("sub_btn") }}
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </section>
  </div>
</template>

<style lang="scss">
.form-border {
  border: 1px solid;
  border-color: rgba(var(--v-border-color), var(--v-border-opacity));
  padding: 15px;
  border-radius: 6px;
}
</style>