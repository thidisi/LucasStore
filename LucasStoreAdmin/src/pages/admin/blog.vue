<!-- YourOtherComponent.vue hoặc App.vue -->
<template>
  <div>
    <VTabs
      v-model="activeTab"
      show-arrows
    >
      <VTab
        v-for="item in filteredTabs"
        :key="item.icon"
        :value="item.tab"
        @click="changeTab(item.tab)"
      >
        <VIcon
          size="20"
          start
          :icon="item.icon"
        />
        {{ item.title }}
      </VTab>
    </VTabs>
    <VDivider />
    <VWindow
      v-model="activeTab"
      class="mt-3 disable-tab-transition"
    >
      <VWindowItem
        v-for="item in filteredTabs"
        :key="item.tab"
        :value="item.tab"
      >
        <component
          :is="item.component"
          :data-edit="dataEdit"
          :reload-data="reloadDataList"
          @submit-success="handleSubmitSuccess"
          @submit-show-edit="handleShowEdit"
        />
      </VWindowItem>
    </VWindow>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import FormCreate from '@/views/pages/admin/blog/FormCreate.vue'
import FormUpdate from '@/views/pages/admin/blog/FormUpdate.vue'
import GetAllList from "@/views/pages/admin/blog/GetAllList.vue"
import GetAllBlog from '@/services/blogs/GetAllBlog'
import { useRoute } from 'vue-router'

export default {
  components: {
    FormCreate,
    GetAllList,
    FormUpdate,
  },
  setup(props, { emit }) {
    const { load_finder } = GetAllBlog()
    
    const route = useRoute()
    const activeTab = ref(route.params.tab || 'list')
    const dataEdit = ref(null)

    // tabs
    const tabs = [
      {
        title: 'List',
        icon: 'bx-user',
        tab: 'list',
        component: 'GetAllList',
      },
      {
        title: 'Create',
        icon: 'bx-lock-open',
        tab: 'create',
        component: 'FormCreate',
      },
      {
        title: 'Edit',
        icon: 'bx-bell',
        tab: 'edit',
        component: 'FormUpdate',
      },
    ]

    const changeTab = tab => {
      reloadDataList.value = false
      activeTab.value = tab
    }

    const shouldShowCreateTabRole = ref(true)
    const shouldShowEditTabRole = ref(true)
    const shouldShowEditTab = ref(false)

    // Filter tabs to middleware
    const filteredTabs = computed(() => {
      return tabs.filter(item => {
        if(item.tab == 'create'){
          return shouldShowCreateTabRole.value
        }
        if (item.tab === 'edit') {
          if(!shouldShowEditTabRole.value){
            return false
          }else {
            return shouldShowEditTab.value
          }
        }
        
        return true
      })
    })

    const reloadDataList = ref(false)

    const handleSubmitSuccess  = () => {
      changeTab('list')
      reloadDataList.value = true
      shouldShowEditTab.value = false
    }

    const handleShowEdit = async data => {
      dataEdit.value = await load_finder(data)
      shouldShowEditTab.value = true
      changeTab('edit')
    }

    return {
      activeTab,
      tabs,
      changeTab,
      handleSubmitSuccess,
      handleShowEdit,
      shouldShowCreateTabRole,
      shouldShowEditTabRole,
      shouldShowEditTab,
      filteredTabs,
      load_finder,
      dataEdit,
      reloadDataList,
    }
  },
}
</script>

<style lang="scss">
.text-danger {
  color:red;
  margin-top: 2px;
  margin-left: 4px;
  font-size: 0.8rem;
}
</style>
