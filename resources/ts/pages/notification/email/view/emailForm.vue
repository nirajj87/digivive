
<script lang="ts" setup>
const  toolbar = [
        [{ font: [] }],
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        ["bold", "italic", "underline", "strike"],
        [{ color: [] }, { background: [] }],
        [{ script:  "sub" }, { script:  "super" }],
        ["blockquote", "code-block"],
        [{ list:  "ordered" }, { list:  "bullet" }],
        [{ indent:  "-1" }, { indent:  "+1" }, { align: [] }],
        ["link", "image", "video"],
        ["clean"],
    ];
   

    import { MdEditor } from 'md-editor-v3';
    import 'md-editor-v3/lib/style.css';

    const text = ref('# Hello Editor');
  
</script>
<template>
      <VCol cols="12">
                    <h5 class="mb-2">{{ $t("email_template") }}</h5>
                    <VCard border >
                        <VCardText>
                            <VRow>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('title') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="6">
                                    <VTextField :label="$t('subject') + ' * ' " />
                                </VCol>
                                <VCol cols="12" md="12">
                                    <!-- <VTextField :label="$t('body') + ' * ' " /> -->

                                    <!-- <quill-editor theme="snow" :toolbar="toolbar"></quill-editor> -->
                                    <MdEditor v-model="text" language="en-US" />
                                </VCol>
                                <VCol cols="12" md="12">
                                    <VTextarea
                                        :label="$t('variables') + ' * ' "
                                        clearable
                                        counter
                                    />
                                </VCol>
                            </VRow>
                        </VCardText>
                    </VCard>
                </VCol>

                <VCol cols="12" class="text-right">
                    <VBtn
                        variant="outlined"
                        color="secondary mr-3"
                        >
                        {{$t("can_btn")}}
                    </VBtn>

                    <VBtn
                        type="submit"
                        color="primary"
                    >
                        {{$t("sub_btn")}}
                    </VBtn>
                </VCol>
</template>