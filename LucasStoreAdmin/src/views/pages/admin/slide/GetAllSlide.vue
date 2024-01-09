<template>
  <div>
    <VDataTable
      v-model:items-per-page="itemsPerPage"
      :headers="headers"
      :items="data"
      item-key="id"
      item-value="title"
    >
      <template #item.image="{ item }">
        <VCard
          class="my-2"
          elevation="2"
          rounded
        >
          <VImg
            :src="'http://localhost:8000/storage/'+`${item.selectable.image}`"
            height="64"
            cover
          />
        </VCard>
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
import GetAllSlider from '@/services/getAllSlider'
import { VDataTable } from 'vuetify/labs/VDataTable'

export default {
  components: { VDataTable },
  emits: ['submit-showEdit'],
  setup(props, { emit }) {
    const { slider, error, load } = GetAllSlider()

    onMounted(() => {
      load()
    })

    const editItem = item => {
      emit('submit-showEdit', item.selectable.id)
    }

    const deleteItem = item => {
      console.log(item.selectable.id)
    }

    return {
      slider,
      load,
      error,
      editItem, 
      deleteItem,
      itemsPerPage: 10,
      headers: [
        {
          title: 'ID',
          align: 'center',
          key: 'code',
        },
        {
          title: 'Title',
          align: 'center',
          key: 'title',
        },
        {
          title: 'Image',
          align: 'center',
          key: 'image',
        },
        {
          title: 'Menu',
          align: 'center',
          sortable: false,
          key: 'major_category',
          value: item => `${item.major_category.name}`,
        },
        {
          title: 'Sort Order',
          align: 'center',
          key: 'sort_order',
        },
        {
          title: 'Status',
          align: 'center',
          key: 'status',
        },
        { title: 'Actions', key: 'actions', sortable: false },
      ],
      data: slider,
    }
  },
}
</script>
