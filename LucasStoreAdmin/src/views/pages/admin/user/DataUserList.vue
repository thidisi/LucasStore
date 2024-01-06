<template>
  <div class="">
    <div class="table-responsive p-4">
      <DataTable 
        :data="users"
        :columns="columns"
        :options="{ responsive:true, dom:'ip'}"
        class="table table-striped table-bordered display"
      >
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Body</th>
          </tr>
        </thead>
      </DataTable>
    </div>
  </div>
</template>

<script>
import axios from '@/plugins/axios'
import DataTable from 'datatables.net-vue3' // Import DataTable từ thư viện
import DataTablesCore from 'datatables.net-bs5'
import ButtonsHtml5 from 'datatables.net-buttons/js/buttons.html5'
import '@/assets/css/datatables.css'

DataTable.use(DataTablesCore)
DataTable.use(ButtonsHtml5)

export default {
  components: {
    DataTable, // Đảm bảo rằng DataTable được định nghĩa ở đây
  },
  setup() {
    const users = ref(null)

    const columns = [
      {
        data: null,
        render: function (data, type, row, meta) {
          return `${meta.row + 1}`
        },
        sortable: true,
      },
      { data: 'title' },
      { data: 'body' },
    ]

    const getDataTableData = async () => {
      try {
        const response = await axios.get('https://jsonplaceholder.typicode.com/posts')

        users.value = response.data
      } catch (error) {
        console.error('Lỗi khi lấy dữ liệu từ API:', error)
      }
    }

    onMounted(getDataTableData)

    return {
      users,
      columns,
    }
  },
}
</script>
