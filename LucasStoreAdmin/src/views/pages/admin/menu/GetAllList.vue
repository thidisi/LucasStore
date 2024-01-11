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
    const isButtonDeleteDisabled = ref(true)

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
      try {
        isButtonDeleteDisabled.value = false
        await destroy(item.selectable.id)
        reloadData()
      } catch (error) {
        console.error('Error:', error)
      } finally {
        setTimeout(() => {
          isButtonDeleteDisabled.value = true
        }, 1000)
      }
    }

    const reloadData = () => {
      load()
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

    return {
      major_category,
      editItem, 
      deleteItem,
      handleStatus,
      reloadData,
      isButtonDeleteDisabled,
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
