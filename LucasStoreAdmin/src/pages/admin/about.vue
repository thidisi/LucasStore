<template>
  <VDataTable
    :headers="headers"
    :items="desserts"
    :sort-by="[{ key: 'calories', order: 'asc' }]"
  >
    <template #top>
      <VToolbar flat>
        <VToolbarTitle>My CRUD</VToolbarTitle>
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
          <template #activator="{ props }">
            <VBtn
              color="primary"
              dark
              class="mb-2"
              v-bind="props"
            >
              New Item
            </VBtn>
          </template>
          <VCard>
            <VCardTitle>
              <span class="text-h5">{{ formTitle }}</span>
            </VCardTitle>

            <VCardText>
              <VContainer>
                <VRow>
                  <VCol
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <VTextField
                      v-model="editedItem.name"
                      label="Dessert name"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <VTextField
                      v-model="editedItem.calories"
                      label="Calories"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <VTextField
                      v-model="editedItem.fat"
                      label="Fat (g)"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <VTextField
                      v-model="editedItem.carbs"
                      label="Carbs (g)"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <VTextField
                      v-model="editedItem.protein"
                      label="Protein (g)"
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
                @click="save"
              >
                Save
              </VBtn>
            </VCardActions>
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
                color="blue-darken-1"
                variant="text"
                @click="closeDelete"
              >
                Cancel
              </VBtn>
              <VBtn
                color="blue-darken-1"
                variant="text"
                @click="deleteItemConfirm"
              >
                OK
              </VBtn>
              <VSpacer />
            </VCardActions>
          </VCard>
        </VDialog>
      </VToolbar>
    </template>
    <template #no-data>
      <VBtn
        color="primary"
        @click="initialize"
      >
        Reset
      </VBtn>
    </template>
  </VDataTable>
</template>

<script>
import { ref, onMounted } from 'vue'
import { VDataTable } from 'vuetify/labs/VDataTable'

export default {
  components: { VDataTable },
  setup() {
    const dialog = ref(false)
    const dialogDelete = ref(false)

    const headers = [
      {
        title: 'Dessert (100g serving)',
        align: 'start',
        sortable: false,
        key: 'name',
      },
      { title: 'Calories', key: 'calories' },
      { title: 'Fat (g)', key: 'fat' },
      { title: 'Carbs (g)', key: 'carbs' },
      { title: 'Protein (g)', key: 'protein' },
      { title: 'Actions', key: 'actions', sortable: false },
    ]

    const desserts = ref([])
    const editedIndex = ref(-1)

    const editedItem = ref({
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    })

    const defaultItem = {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    }

    const formTitle = ref('')

    const initialize = () => {
      desserts.value = [
        function initialize () {
          desserts.value = [
            {
              name: 'Frozen Yogurt',
              calories: 159,
              fat: 6,
              carbs: 24,
              protein: 4,
            },
            {
              name: 'Ice cream sandwich',
              calories: 237,
              fat: 9,
              carbs: 37,
              protein: 4.3,
            },
            {
              name: 'Eclair',
              calories: 262,
              fat: 16,
              carbs: 23,
              protein: 6,
            },
            {
              name: 'Cupcake',
              calories: 305,
              fat: 3.7,
              carbs: 67,
              protein: 4.3,
            },
            {
              name: 'Gingerbread',
              calories: 356,
              fat: 16,
              carbs: 49,
              protein: 3.9,
            },
            {
              name: 'Jelly bean',
              calories: 375,
              fat: 0,
              carbs: 94,
              protein: 0,
            },
            {
              name: 'Lollipop',
              calories: 392,
              fat: 0.2,
              carbs: 98,
              protein: 0,
            },
            {
              name: 'Honeycomb',
              calories: 408,
              fat: 3.2,
              carbs: 87,
              protein: 6.5,
            },
            {
              name: 'Donut',
              calories: 452,
              fat: 25,
              carbs: 51,
              protein: 4.9,
            },
            {
              name: 'KitKat',
              calories: 518,
              fat: 26,
              carbs: 65,
              protein: 7,
            },
          ]
        },
      ]
    }

    const editItem = item => {
      editedIndex.value = desserts.value.indexOf(item)
      editedItem.value = { ...item }
      dialog.value = true
    }

    const deleteItem = item => {
      editedIndex.value = desserts.value.indexOf(item)
      editedItem.value = { ...item }
      dialogDelete.value = true
    }

    const deleteItemConfirm = () => {
      desserts.value.splice(editedIndex.value, 1)
      closeDelete()
    }

    const close = () => {
      dialog.value = false
      editedItem.value = { ...defaultItem }
      editedIndex.value = -1
    }

    const closeDelete = () => {
      dialogDelete.value = false
      editedItem.value = { ...defaultItem }
      editedIndex.value = -1
    }

    const save = () => {
      if (editedIndex.value > -1) {
        Object.assign(desserts.value[editedIndex.value], editedItem.value)
      } else {
        desserts.value.push({ ...editedItem.value })
      }
      close()
    }

    onMounted(() => {
      initialize()
    })

    return {
      dialog,
      dialogDelete,
      headers,
      desserts,
      editedIndex,
      editedItem,
      defaultItem,
      formTitle,
      editItem,
      deleteItem,
      deleteItemConfirm,
      close,
      closeDelete,
      save,
      initialize,
    }
  },
}
</script>
