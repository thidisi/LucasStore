<template>
  <VForm @submit.prevent="handleSubmit">
    <VRow>
      <VCol cols="6">
        <VTextField
          v-model="form.title"
          label="Slide title"
          placeholder=""
          :rules="v$.title.$invalid ? [v$.title.required.$invalid ? v$.title.required.$message : '' + v$.title.minLength.$message] : ''"
        />
      </VCol>

      <VCol cols="6">
        <VSelect
          v-model="form.major_category_name"
          label="Choose Major category"
          :items="response"
          placeholder="Select"
          :rules="v$.major_category_name.required.$invalid ? [v$.major_category_name.required.$message] : ''"
        />
      </VCol>

      <VCol cols="6">
        <VTextField
          v-model="form.slug"
          label="Slide slug"
          placeholder=""
          :rules="v$.slug.required.$invalid ? [v$.slug.required.$message] : ''"
        />
      </VCol>

      <VCol cols="6">
        <VSelect
          v-model="form.sort_order"
          label="Choose Sort Order"
          :items="dataSorts"
          placeholder="Select"
          :rules="v$.sort_order.required.$invalid ? [v$.sort_order.required.$message] : ''"
        />
      </VCol>

      <VCol>
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
        >
          Reset
        </VBtn>
      </VCol>
    </VRow>
  </VForm>
</template>

<script>
import axios from '@/plugins/axios'
import { useVuelidate } from '@vuelidate/core'
import { minLength, required } from '@vuelidate/validators'
import { useStore } from 'vuex'

export default {
  components: {
  },
  setup(props, { emit }) {
    const store = useStore()
    const refInputEl = ref()
    const response = ref()
    const responses = ref()
    const dataSorts = ref()
    const renderImg = ref()


    const form = ref({
      title: '',
      slug: '',
      image: null,
      major_category_name: null,
      sort_order: null,
    })

    const rules = computed(() => {
      return { 
        title: {
          required,
          minLength: minLength(4),
        },
        slug: {
          required,
        },
        image: {
          required,
        },
        major_category_name: {
          required,
        },
        sort_order: {
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
      form.value.title = null
      form.value.slug = null
      form.value.image = null
      form.value.major_category_name = null
      form.value.sort_order = null
      v$.value.$reset()
    }

    const handleSubmit = async () => {
      try {
        const checkValid = await v$.value.$validate()

        if (checkValid) {
          isCreateButtonDisabled.value = true

          const { title, slug, image, major_category_name, sort_order } = form.value
          const major_category_id = responses.value.find(item => item.name === major_category_name).id

          const formData = new FormData()

          formData.append('title', title)
          formData.append('slug', slug)
          formData.append('image', image)
          formData.append('major_category_id', major_category_id)
          formData.append('sort_order', sort_order)

          await axios.post('/slides', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })

          resetForm()
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

    const loadData = async () => {
      try {
        return await axios.get('/slides')
      } catch (error) {
        console.error('Error loading data:', error)
      }
    }

    onMounted(async () => {
      try {
        const api = await loadData()

        response.value = api.data.menu.map(item => item.name)
        responses.value = api.data.menu.map(item => ({ id: item.id, name: item.name }))

        dataSorts.value = Object.values(api.data.sortOrder)
        
      } catch (error) {
        console.error('Error in onMounted:', error)
      }
    })

    return {
      form,
      handleSubmit,
      v$,
      isCreateButtonDisabled,
      resetForm,
      refInputEl,
      handleFileChange,
      resetImage,
      response,
      dataSorts,
      renderImg,
    }
  },
}
</script>

<style lang="scss">
.text-danger {
  color:red;
  margin-top: 2px;
  margin-left: 4px;
  font-size: 0.9rem;
}
.v-avatar  {
  background-image: url('http://localhost:5173/src/assets/images/avatars/avatar-1.png');
  background-size: cover;
}
</style>
