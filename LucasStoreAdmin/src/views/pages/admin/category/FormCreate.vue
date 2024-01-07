<template>
  <VForm @submit.prevent="handleSubmit">
    <VCol
      cols="12"
      md="6"
    >
      <VTextField
        v-model="form.name"
        label="Cateogry name"
        placeholder=""
      />
      <span
        v-for="error in v$.name.$errors"
        :key="error.$uid"
        class="text-danger"
      >
        {{ error.$message }}
      </span> 
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
      />
      <span
        v-for="error in v$.major_category_name.$errors"
        :key="error.$uid"
        class="text-danger"
      >
        {{ error.$message }}
      </span>
    </VCol>

    <VCol>
      <VCardText class="d-flex">
        <!-- 👉 Avatar -->
        <VAvatar
          rounded="lg"
          size="100"
          class="me-6"
          :image="form.avatar"
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
              @input="changeAvatar"
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
import axios from '@/plugins/axios'
import { useStore } from 'vuex'
import { useVuelidate } from '@vuelidate/core'
import { minLength, required } from '@vuelidate/validators'
import { allowedFileTypes, maxSize } from '@/validators'

export default {
  components: {
  },
  setup(props, { emit }) {
    const store = useStore()
    const refInputEl = ref()
    const response = ref()
    const responses = ref()

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
          allowedFileTypes: allowedFileTypes(['image/jpeg', 'image/gif', 'image/png']),
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
      form.avatar = event.target.files[0]
    }

    const changeAvatar = file => {
      const fileReader = new FileReader()
      const { files } = file.target
      if (files && files.length) {
        fileReader.readAsDataURL(files[0])
        fileReader.onload = () => {
          if (typeof fileReader.result === 'string')
            form.value.avatar = fileReader.result
        }
      }
    }

    const resetAvatar = () => {
      form.value.avatar = null
    }

    const resetForm = () => {
      form.value.name = ''
      form.value.avatar = ''
      form.value.major_category_name = ''
      v$.value.$reset()
    }

    const handleSubmit = async () => {
      try {
        const checkValid = await v$.value.$validate()

        if (checkValid) {
          isCreateButtonDisabled.value = true

          const { name, avatar, major_category_name } = form.value
          const major_category_id = responses.value.find(item => item.name === major_category_name).id

          await axios.post('/categories', { name, avatar, major_category_id })

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
        return await axios.get('/major_categories')
      } catch (error) {
        console.error('Error loading data:', error)
      }
    }

    onMounted(async () => {
      try {
        const api = await loadData()

        response.value = api.data.major_categories.map(item => item.name)
        responses.value = api.data.major_categories.map(item => ({ id: item.id, name: item.name }))
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
      changeAvatar,
      resetAvatar,
      response,
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
  background-image: url(http://localhost:5173/src/assets/images/avatars/avatar-1.png);
  background-size: cover;
}
</style>