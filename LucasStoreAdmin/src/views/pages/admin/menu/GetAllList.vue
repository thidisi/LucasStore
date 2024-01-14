<template>
  <div>
    <VDataTable
      v-model:items-per-page="itemsPerPage"
      :headers="headers"
      :items="data"
      item-key="id"
      item-value="name"
      @reload-data="reloadData"
    >
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
      <template #top>
        <VSpacer />
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
                @click="deleteConfirm(id)"
              >
                OK
              </VBtn>
              <VSpacer />
            </VCardActions>
          </VCard>
        </VDialog>
      </template>
    </VDataTable>
  </div>
</template>

<script>
import GetAllMajorCategory from '@/services/major_categories/GetAllMajorCategory'
import PutMajorCategories from '@/services/major_categories/PutMajorCategory'
import { VDataTable } from 'vuetify/labs/VDataTable'

export default {
  components: { VDataTable },
  props: ['reload-data'],
  emits: ['submit-showEdit'],
  setup(props, { emit }) {
    const { major_category, load } = GetAllMajorCategory()
    const { destroy } = PutMajorCategories()
    const isButtonDisabled = ref(true)
    const dialogDelete = ref(false)
    const id = ref()

    onMounted(() => {
      load()
    })

    watch(() => {
      if (props.reloadData) {
        reloadData()
      }
    }, { deep: true })

    const editItem = item => {
      // eslint-disable-next-line vue/custom-event-name-casing
      emit('submit-showEdit', item.selectable.id)
    }

    const deleteItem = async item => {
      dialogDelete.value = true
      id.value = item.selectable?.id
    }

    const closeDelete = () => {
      dialogDelete.value = false
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

    const reloadData = () => {
      load()
    }

    return {
      major_category,
      editItem, 
      id,
      dialogDelete,
      deleteItem,
      deleteConfirm,
      closeDelete,
      reloadData,
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
          title: 'Status',
          key: 'status',
        },
        { title: 'Actions', key: 'actions', sortable: false },
      ],
      data: major_category,
    }
  },
}
</script>
