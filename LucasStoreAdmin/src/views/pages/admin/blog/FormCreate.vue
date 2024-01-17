<template>
  <VForm @submit.prevent="handleSubmit">
    <VRow>
      <VCol cols="6">
        <QuillEditor
          ref="quill"
          v-model:content="form.content"
          content-type="html"
          theme="snow"
        />
        <span
          v-for="error in v$.content.$errors"
          :key="error.$uid"
          class="text-danger"
        >
          {{ error.$message }}
        </span>
      </VCol>

      <VCol cols="6">
        <VTextField
          v-model="form.title"
          label="Slide title"
          placeholder=""
          :rules="v$.title.$invalid ? [v$.title.required.$invalid ? v$.title.required.$message : '' + v$.title.minLength.$message] : ''"
        />
      </VCol>

      <VCol
        cols="6"
        class="mt-4"
      >
        <VCardText class="d-flex">
          <!-- 👉 Image -->
          <VAvatar
            v-if="form.image"
            rounded="lg"
            size="100"
            class="me-6"
            :image="renderImg"
          />
          <!-- 👉 Upload Photo -->
          <form class="d-flex flex-column justify-center gap-3">
            <div class="d-flex flex-wrap gap-2">
              <VBtn
                color="primary"
                @click="refInputEl?.click()"
              >
                <VIcon
                  icon="bx-cloud-upload"
                  class="d-sm-none"
                />
                <span class="d-none d-sm-block">Upload new photo</span>
              </VBtn>

              <input
                ref="refInputEl"
                type="file"
                name="file"
                accept=".jpeg,.png,.jpg,GIF"
                hidden
                @input="handleFileChange"
              >
              <VBtn
                type="reset"
                color="error"
                variant="tonal"
                @click="resetImage"
              >
                <span class="d-none d-sm-block">Reset</span>
                <VIcon
                  icon="bx-refresh"
                  class="d-sm-none"
                />
              </VBtn>
            </div>

            <p class="text-body-1 mb-0">
              Allowed JPG, GIF or PNG. Max size of 800K
            </p>
            <span
              v-for="error in v$.image.$errors"
              :key="error.$uid"
              class="text-danger"
            >
              {{ error.$message }}
            </span>
          </form>
        </VCardText>
      </VCol>

      <VDivider />
      
      <VCol
        cols="12"
        class="d-flex gap-4"
      >
        <VBtn
          type="submit"
          :disabled="isCreateButtonDisabled"
        >
          Submit
        </VBtn>

        <VBtn
          type="reset"
          color="secondary"
          variant="tonal"
          @click="resetForm"
        >
          Reset
        </VBtn>
      </VCol>
    </VRow>
  </VForm>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import PostBlog from '@/services/blogs/PostBlog'
import { useVuelidate } from '@vuelidate/core'
import { minLength, required } from '@vuelidate/validators'
import { allowedFileTypes, maxSize } from '@/validators'

export default {
  components: {
    QuillEditor,
  },
  emits: ['submit-success'],
  setup(props, { emit }) {
    const { load } = PostBlog()

    const refInputEl = ref()
    const quill = ref(null)
    const renderImg = ref()

    const editorOption = {
      theme: 'snow',
    }

    const form = ref({
      title: '',
      image: null,
      content: null,
    })

    const rules = computed(() => {
      return { 
        title: {
          required,
          minLength: minLength(4),
        },
        image: {
          required,
          allowedFileTypes: allowedFileTypes(['jpeg', 'png', 'jpg', 'gif']),
          maxSize: maxSize(800 * 1024),
        },
        content: {
          required,
        },
      }
    })

    const isCreateButtonDisabled = ref(false)

    const v$ = useVuelidate(rules, form)

    const handleFileChange = event => {
      form.value.image = event.target.files[0]

      const fileReader = new FileReader()
      const { files } = event.target
      if (files && files.length) {
        fileReader.readAsDataURL(files[0])
        fileReader.onload = () => {
          if (typeof fileReader.result === 'string')
            renderImg.value = fileReader.result
        }
      }
    }

    const resetImage = () => {
      form.value.image = null
    }

    const resetForm = () => {
      form.value.title = ''
      form.value.image = null
      form.value.content = null
      quill.value.setContents('')
      v$.value.$reset()
    }

    const handleSubmit = async () => {
      try {
        const checkValid = await v$.value.$validate()

        if (checkValid) {
          isCreateButtonDisabled.value = true

          const { title, image, content } = form.value

          const formData = new FormData()

          formData.append('title', title)
          formData.append('image', image)
          formData.append('content', content)
          await load(formData)
          resetForm()
          // eslint-disable-next-line vue/custom-event-name-casing
          emit('submit-success')
        }
      } catch (error) {
        resetForm()
        console.error('Error:', error)
      }finally {
        setTimeout(() => {
          isCreateButtonDisabled.value = false
        }, 1000)
      }

    }

    return {
      form,
      handleSubmit,
      v$,
      isCreateButtonDisabled,
      resetForm,
      refInputEl,
      quill,
      handleFileChange,
      resetImage,
      renderImg,
      editorOption,
    }
  },
}
</script>
