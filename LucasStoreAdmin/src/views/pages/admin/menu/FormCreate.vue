<template>
  <VForm @submit.prevent="handleSubmit">
    <VRow>
      <!-- 👉 First Name -->
      <VCol
        cols="12"
        md="6"
      >
        <VTextField
          v-model="form.name"
          label="Major cateogry name"
          placeholder="John"
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
          v-model="form.status"
          label="Major cateogry status"
          :items="['show', 'hot_default', 'hide']"
          placeholder="Select Status"
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
import { useStore } from 'vuex'
import { useVuelidate } from '@vuelidate/core'
import { email, minLength, required } from '@vuelidate/validators'

export default {
  components: {
  },
  setup(props, { emit }) {
    const store = useStore()

    const form = ref({
      name: '',
      status: 'show',
    })

    const rules = computed(() => {
      return { 
        name: {
          required,
          minLength: minLength(4),
        },
        status: {
          required,
        },
      }
    })

    const isCreateButtonDisabled = ref(false)

    const v$ = useVuelidate(rules, form)

    const resetForm = () => {
      form.value.name = ''
      v$.value.$reset()
    }

    const handleSubmit = async () => {
      try {
        const checkValid = await v$.value.$validate()

        if (checkValid) {
          isCreateButtonDisabled.value = true

          const { name, status } = form.value

          await axios.post('/major_categories', { name, status })

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

    return {
      form,
      handleSubmit,
      v$,
      isCreateButtonDisabled,
      resetForm,
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
.v-select__selection-text {
  text-transform: capitalize;
}
</style>
