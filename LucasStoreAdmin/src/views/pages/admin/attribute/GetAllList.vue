<template>
  <div>
    <VDataTable
      v-model:items-per-page="itemsPerPage"
      :headers="headers"
      :items="data"
      item-key="id"
      item-value="name"
    >
      <template #top>
        <VToolbar flat>
          <VToolbarTitle>Attribute List</VToolbarTitle>
          <VDivider
            class="mx-4"
            inset
            vertical
          />
          <VSpacer />
          <VDialog
            v-model="dialog"
            max-width="500px"
          >
            <template #activator="{}">
              <VBtn
                color="primary"
                dark
                class="mb-2"
                @click="showDialog"
              >
                New Item
              </VBtn>
            </template>
            <VCard>
              <VForm @submit.prevent="save(form.method, form?.id)">
                <VCardTitle>
                  <span class="text-h5">{{ form.title }}</span>
                </VCardTitle>
  
                <VCardText>
                  <VContainer>
                    <VRow>
                      <VCol
                        cols="12"
                        sm="6"
                      >
                        <VTextField
                          v-model="form.name"
                          label="Name"
                          :rules="v$.name.required.$invalid ? [v$.name.required.$message] : ''"
                        />
                      </VCol>
                      <VCol
                        cols="12"
                        sm="6"
                      >
                        <VSelect
                          v-model="form.type"
                          label="Type"
                          :items="typeStore"
                          :rules="v$.type.required.$invalid ? [v$.type.required.$message] : ''"
                        />
                      </VCol>
                      <VCol cols="12">
                        <VSelect
                          v-model="form.parent"
                          label="Parent"
                          :items="attrNames"
                        />
                      </VCol> 
                    </VRow>
                  </VContainer>
                </VCardText>
  
                <VCardActions>
                  <VSpacer />
                  <VBtn
                    color="blue-darken-1"
                    variant="text"
                    @click="close"
                  >
                    Cancel
                  </VBtn>
                  <VBtn
                    type="submit"
                    color="blue-darken-1"
                    variant="text"
                    :disabled="!isButtonDisabled"
                  >
                    Save
                  </VBtn>
                </VCardActions>
              </VForm>
            </VCard>
          </VDialog>
          <VDialog
            v-model="dialogDelete"
            max-width="500px"
          >
            <VCard>
              <VCardTitle class="text-h5">
                Are you sure you want to delete this item?
              </VCardTitle>
              <VCardActions>
                <VSpacer />
                <VBtn
                  color="blue darken-1"
                  text
                  @click="closeDelete"
                >
                  Cancel
                </VBtn>
                <VBtn
                  color="blue darken-1"
                  text
                  :disabled="!isButtonDisabled"
                  @click="deleteConfirm(form?.id)"
                >
                  OK
                </VBtn>
                <VSpacer />
              </VCardActions>
            </VCard>
          </VDialog>
        </VToolbar>
      </template>
      <template #item.status="{ item }">
        <div class="demo-space-x">
          <VSwitch
            :key="item.selectable.status"
            v-model="item.selectable.status"
            :value="item.selectable.status === 'active' ? 'active': 'close'"
            :label="item.selectable.status === 'active' ? 'active': 'close'"
            :color="item.selectable.status === 'active' ? 'success' : ''"
            :disabled="item.selectable?.isButtonDisabled"  
            @click="handleStatus(item)"
          />
        </div>
      </template>
      <template #item.actions="{ item }">
        <VIcon
          size="small"
          class="me-2"
          @click="editItem(item)"
        >
          mdi-pencil
        </VIcon>
        <VIcon
          size="small"  
          @click="deleteItem(item)"
        >
          mdi-delete
        </VIcon>
      </template>
    </VDataTable>
  </div>
</template>

<script>
import GetAllAttribute from '@/services/attributes/GetAllAttribute'
import PostAttributes from '@/services/attributes/PostAttribute'
import { VDataTable } from 'vuetify/labs/VDataTable'
import { useVuelidate } from '@vuelidate/core'
import {  required } from '@vuelidate/validators'

export default {
  components: { VDataTable },
  emits: ['submit-showEdit'],
  setup(props, { emit }) {
    const { load } = GetAllAttribute()
    const { load_store, load_update, destroy, changStatus } = PostAttributes()
    const isButtonDisabled = ref(true)
    const dialog = ref(false)
    const dialogDelete = ref(false)
    const attrNames = ref()
    const attrs = ref()
    const attrValues = ref()

    const form = ref({
      method: 'post',
      title: 'Create',
      name: '',
      type: 'size',
      parent: 'Empty',
      id: null,
    })

    const typeStore = ['size', 'color']

    const rules = computed(() => {
      return { 
        name: {
          required,
        },
        type: {
          required,
        },
      }
    })

    const v$ = useVuelidate(rules, form)

    onMounted(() => {
      reloadData()
    })

    const reloadData = async () => {
      let result = await load()
      attrNames.value = result.attr.map(item => item.name)
      attrs.value = result.attr.map(item => ({ id: item.id, name: item.name }))
      attrValues.value = result.attrValue
    }

    const showDialog = () => {
      form.value.title = 'Create'
      form.value.name = ''
      form.value.type = 'size'
      form.value.parent = 'Empty'
      form.value.method = 'post'
      form.value.id = null

      if(attrNames.value.indexOf('Empty') == -1) {
        attrNames.value.unshift('Empty')
      }
      dialog.value = true
      v$.value.$reset()
    }

    const editItem = item => {
      const indexToRemove = attrNames.value.indexOf('Empty')

      v$.value.$reset()
      form.value.title = 'Edit'
      form.value.name = item.selectable?.name
      form.value.type = item.selectable?.type
      form.value.parent = item.selectable?.parent?.name
      form.value.id = item.selectable?.id
      form.value.method = 'put'
      if (indexToRemove !== -1) {
        attrNames.value.splice(indexToRemove, 1)
      }
      dialog.value = true
    }

    const deleteItem = item => {
      dialogDelete.value = true
      form.value.id = item.selectable?.id
    }

    const deleteConfirm = async id => {
      try {
        isButtonDisabled.value = false
        dialogDelete.value = false
        await destroy(id)
        reloadData()
      } catch (error) {
        console.error('Error:', error)
      } finally {
        setTimeout(() => {
          isButtonDisabled.value = true
        }, 1000)
      }
    }

    const closeDelete = () => {
      dialogDelete.value = false
    }

    const handleStatus = async item => {
      try {
        item.selectable.isButtonDisabled = true
        await changStatus(item.selectable.id)
        reloadData()
      } catch (error) {
        console.error('Error:', error)
      } finally {
        setTimeout(() => {
          item.selectable.isButtonDisabled = false
        }, 3000)
      }
    }

    const close = () => {
      dialog.value = false
    }

    const save = async (method, id = null) => {
      try {
        const checkValid = await v$.value.$validate()

        isButtonDisabled.value = false
        if(checkValid) {
          const { name, type, parent } = form.value
  
          const parent_id = attrs.value.find(item => item.name === parent)?.id
          const formData = new FormData()
  
          formData.append('name', name)
          formData.append('type', type)
          if(parent_id){
            formData.append('parent_id', parent_id)
          }
          if (method == 'post') {
            await load_store(formData)
          }else {
            formData.append('_method', 'PUT')
            await load_update(id, formData)
          }
          reloadData()
          close()
        }
      } catch (error) {
        console.error('Error:', error)
      }finally {
        setTimeout(() => {
          isButtonDisabled.value = true
        }, 1000)
      }
    }

    return {
      attrs,
      attrNames,
      attrValues,
      editItem, 
      deleteItem,
      closeDelete,
      deleteConfirm,
      handleStatus,
      reloadData,
      showDialog,
      form,
      typeStore,
      dialog,
      dialogDelete,
      close,
      save,
      v$,
      isButtonDisabled,
      itemsPerPage: 10,
      headers: [
        {
          title: 'ID',
          align: 'center',
          key: 'code',
        },
        {
          title: 'name',
          align: 'center',
          key: 'name',
        },
        {
          title: 'type',
          align: 'center',
          key: 'type',
        },
        {
          title: 'Parent',
          align: 'center',
          sortable: false,
          key: 'parent_id',
          value: item => `${item.parent?.name ? item.parent.name : 'null'}`,
        },
        {
          title: 'Status',
          key: 'status',
        },
        { title: 'Actions', key: 'actions', sortable: false },
      ],
      data: attrValues,
    }
  },
}
</script>
