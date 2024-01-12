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
              <VCardTitle>
                <span class="text-h5">{{ createAtrr.title }}</span>
              </VCardTitle>

              <VCardText>
                <VContainer>
                  <VRow>
                    <VCol
                      cols="12"
                      sm="6"
                    >
                      <VTextField
                        v-model="createAtrr.name"
                        label="Name"
                      />
                    </VCol>
                    <VCol
                      cols="12"
                      sm="6"
                    >
                      <VSelect
                        v-model="createAtrr.replace"
                        label="Replace"
                        :items="['show', 'hot_default', 'hide']"
                      />
                    </VCol>
                    <VCol cols="12">
                      <VTextField
                        v-model="createAtrr.descriptions"
                        label="Description(* Can Be Empty)"
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
                  color="blue-darken-1"
                  variant="text"
                  @click="save(createAtrr.method)"
                >
                  Save
                </VBtn>
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
            :value="item.selectable.status"
            :label="item.selectable.status"
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
          v-if="isButtonDeleteDisabled"
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
import { VDataTable } from 'vuetify/labs/VDataTable'

export default {
  components: { VDataTable },
  emits: ['submit-showEdit'],
  setup(props, { emit }) {
    const { attributes, load } = GetAllAttribute()
    const isButtonDeleteDisabled = ref(true)
    const dialog = ref(false)

    const createAtrr = ref({
      method: 'post',
      title: 'Create',
      name: '',
      replace: null,
      descriptions: '',
    })

    onMounted(() => {
      load()
    })

    const editItem = item => {
      createAtrr.value.title = 'Edit'
      createAtrr.value.name = 'Lala'
      createAtrr.value.replace = 'Show'
      createAtrr.value.descriptions = 'Lolo'
      createAtrr.value.method = 'put'
      dialog.value = true
    }

    const showDialog = () => {
      createAtrr.value.title = 'Create'
      createAtrr.value.name = ''
      createAtrr.value.replace = null
      createAtrr.value.descriptions = ''
      createAtrr.value.method = 'post'

      dialog.value = true
    }

    const deleteItem = async item => {
      // try {
      //   isButtonDeleteDisabled.value = false
      //   await destroy(item.selectable.id)
      //   reloadData()
      // } catch (error) {
      //   console.error('Error:', error)
      // } finally {
      //   setTimeout(() => {
      //     isButtonDeleteDisabled.value = true
      //   }, 1000)
      // }
    }

    const reloadData = () => {
      load()
    }

    const handleStatus = async item => {
      // try {
      //   item.selectable.isButtonDisabled = true
      //   await changStatus(item.selectable.id)
      //   reloadData()
      // } catch (error) {
      //   console.error('Error:', error)
      // } finally {
      //   setTimeout(() => {
      //     item.selectable.isButtonDisabled = false
      //   }, 3000)
      // }
    }

    const close = () => {
      dialog.value = false
    }

    const save = method => {
      if (method == 'post') {
        console.log(1)
      }else {
        console.log(2)
      }
      reloadData()
      close()
    }

    return {
      attributes,
      editItem, 
      deleteItem,
      handleStatus,
      reloadData,
      showDialog,
      createAtrr,
      dialog,
      close,
      save,
      isButtonDeleteDisabled,
      itemsPerPage: 3,
      headers: [
        {
          title: 'ID',
          align: 'center',
          key: 'id',
        },
        {
          title: 'name',
          align: 'center',
          key: 'name',
        },
        {
          title: 'Replace',
          align: 'center',
          sortable: false,
          key: 'replace_id',
          value: item => `${item.replace?.name ? item.replace.name : 'null'}`,
        },
        {
          title: 'Status',
          key: 'status',
        },
        { title: 'Actions', key: 'actions', sortable: false },
      ],
      data: attributes,
    }
  },
}
</script>
