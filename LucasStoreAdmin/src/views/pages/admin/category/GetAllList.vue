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
      <template #item.avatar="{ item }">
        <VCard
          class="my-2"
          elevation="2"
          rounded
        >
          <VImg
            :src="'http://localhost:8000/storage/'+`${item.selectable.avatar}`"
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
import GetAllCategory from '@/services/categories/GetAllCategory'
import PutCategory from '@/services/categories/PutCategory'
import { VDataTable } from 'vuetify/labs/VDataTable'

export default {
  components: { VDataTable },
  props: ['reload-data'],
  emits: ['submit-showEdit'],
  setup(props, { emit }) {
    const { categories, load } = GetAllCategory()
    const { changStatus, destroy } = PutCategory()
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
      categories,
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
          title: 'avatar',
          align: 'center',
          key: 'avatar',
        },
        {
          title: 'Menu',
          align: 'center',
          sortable: false,
          key: 'major_category',
          value: item => `${item.major_category?.name}`,
        },
        {
          title: 'Status',
          key: 'status',
        },
        { title: 'Actions', key: 'actions', sortable: false },
      ],
      data: categories,
    }
  },
}
</script>
