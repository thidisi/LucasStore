<template>
  <VForm @submit.prevent="handleSubmit">
    <VCol
      cols="12"
      md="6"
    >
      <VTextField
        v-model="form.name"
        label="Cateogry name"
        :rules="v$.name.$invalid ? [v$.name.required.$invalid ? v$.name.required.$message : '' + v$.name.minLength.$message] : ''"
      />
    </VCol>

    <VCol
      cols="12"
      md="6"
    >
      <VSelect
        v-model="form.major_category_name"
        label="Choose Major category"
        :items="response"
        placeholder="Select Menu"
        :rules="v$.major_category_name.required.$invalid ? [v$.major_category_name.required.$message] : ''"
      />
    </VCol>

    <VCol>
      <VCardText class="d-flex">
        <!-- 👉 Avatar -->
        <VAvatar
          v-if="form.avatar"
          rounded="lg"
          size="100"
          class="me-6"
          :image="renderImg"
        />

        <!-- 👉 Upload Photo -->
        <form class="d-flex flex-column justify-center gap-5">
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
            <span
              v-for="error in v$.avatar.$errors"
              :key="error.$uid"
              class="text-danger"
            >
              {{ error.$message }}
            </span>

            <VBtn
              type="reset"
              color="error"
              variant="tonal"
              @click="resetAvatar"
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
        </form>
      </VCardText>
    </VCol>

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
  </VForm>
</template>

<script>
import { useStore } from 'vuex'
import { useVuelidate } from '@vuelidate/core'
import { minLength, required } from '@vuelidate/validators'
import { allowedFileTypes, maxSize } from '@/validators'
import PostCategories from '@/services/categories/PostCategory'

export default {
  components: {
  },
  emits: ['submit-success'],
  setup(props, { emit }) {
    const { load, getMenu } = PostCategories()

    const store = useStore()
    const refInputEl = ref()
    const response = ref()
    const responses = ref()
    const renderImg = ref()

    const form = ref({
      name: '',
      avatar: null,
      major_category_name: null,
    })

    const rules = computed(() => {
      return { 
        name: {
          required,
          minLength: minLength(4),
        },
        avatar: {
          allowedFileTypes: allowedFileTypes(['jpeg', 'png', 'jpg', 'gif']),
          maxSize: maxSize(800 * 1024),
        },
        major_category_name: {
          required,
        },
      }
    })

    const isCreateButtonDisabled = ref(false)

    const v$ = useVuelidate(rules, form)

    const handleFileChange = event => {
      form.value.avatar = event.target.files[0]

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

    const resetAvatar = () => {
      form.value.avatar = null
    }

    const resetForm = () => {
      form.value.name = null
      form.value.avatar = null
      form.value.major_category_name = null
      v$.value.$reset()
    }

    const handleSubmit = async () => {
      try {
        const checkValid = await v$.value.$validate()

        if (checkValid) {
          isCreateButtonDisabled.value = true

          const { name, avatar, major_category_name } = form.value
          const major_category_id = responses.value.find(item => item.name === major_category_name).id
          const formData = new FormData()

          formData.append('name', name)
          formData.append('avatar', avatar ? avatar : '')
          formData.append('major_category_id', major_category_id)
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

    const loadData = async data => {
      try {
        response.value = data.major_categories.map(item => item.name)
        responses.value = data.major_categories.map(item => ({ id: item.id, name: item.name }))
      } catch (error) {
        console.error('Error in onMounted:', error)
      }
    }

    onMounted(async () => {
      let menu = await getMenu()
      loadData(menu)
    })

    return {
      form,
      handleSubmit,
      v$,
      isCreateButtonDisabled,
      resetForm,
      refInputEl,
      handleFileChange,
      resetAvatar,
      response,
      renderImg,
    }
  },
}
</script>
