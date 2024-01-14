<template>
  <div>
    <VDataTable
      v-model:items-per-page="itemsPerPage"
      :headers="headers"
      :items="data"
      item-key="id"
      item-value="title"
      @reload-data="reloadData"
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
import GetAllSlider from '@/services/slider/GetAllSlider'
import PutSlider from '@/services/slider/PutSlider'
import { VDataTable } from 'vuetify/labs/VDataTable'

export default {
  components: { VDataTable },
  props: ['reload-data'],
  emits: ['submit-showEdit'],
  setup(props, { emit }) {
    const { slider, load } = GetAllSlider()
    const { changStatus, destroy } = PutSlider()
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
      slider,
      editItem, 
      id,
      dialogDelete,
      deleteItem,
      deleteConfirm,
      closeDelete,
      handleStatus,
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
          value: item => `${item.major_category?.name}`,
        },
        {
          title: 'Sort Order',
          align: 'center',
          key: 'sort_order',
        },
        {
          title: 'Status',
          key: 'status',
        },
        { title: 'Actions', key: 'actions', sortable: false },
      ],
      data: slider,
    }
  },
}
</script>
