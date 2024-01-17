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
          :rules="v$.name.$invalid ? [v$.name.required.$invalid ? v$.name.required.$message : '' + v$.name.minLength.$message] : ''"
        />
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
          :rules="v$.status.required.$invalid ? [v$.status.required.$message] : ''"
        />
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
import { minLength, required } from '@vuelidate/validators'
import PutMajorCategories from '@/services/major_categories/PutMajorCategory'

export default {
  props: {
    dataEdit: {
      type: Object,
      default: null,
    },
  },
  emits: ['submit-success'],
  setup(props, { emit }) {
    const store = useStore()

    const { load } = PutMajorCategories()

    const form = ref({
      name: props.dataEdit?.major_category.name,
      status: props.dataEdit?.major_category.status,
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
      form.value.name = props.dataEdit?.major_category.name,
      form.value.status = props.dataEdit?.major_category.status,
      v$.value.$reset()
    }

    const handleSubmit = async () => {
      try {
        const checkValid = await v$.value.$validate()

        if (checkValid) {
          isCreateButtonDisabled.value = true

          const { name, status } = form.value

          const formData = new FormData()

          formData.append('name', name)
          formData.append('status', status)
          formData.append('_method', 'PUT')

          await load(props.dataEdit.major_category.id, formData)
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
    }
  },
}
</script>
