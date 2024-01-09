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
          @submit-success="handleSubmitSuccess"
          @submit-show-edit="handleShowEdit"
        />
      </VWindowItem>
    </VWindow>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import FormCreate from '@/views/pages/admin/slide/FormCreate.vue'
import GetAllSlide from "@/views/pages/admin/slide/GetAllSlide.vue"
import { useRoute } from 'vue-router'

export default {
  components: {
    FormCreate,
    GetAllSlide,
  },
  setup(props, { emit }) {
    
    const route = useRoute()
    const activeTab = ref(route.params.tab || 'list')

    // tabs
    const tabs = [
      {
        title: 'List',
        icon: 'bx-user',
        tab: 'list',
        component: 'GetAllSlide',
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
        component: 'FormCreate',
      },
    ]

    const changeTab = tab => {
      activeTab.value = tab
    }

    // Middleware (ví dụ: ẩn tab "create" dựa trên điều kiện)
    const shouldShowCreateTabRole = ref(true)
    const shouldShowEditTabRole = ref(true)
    const shouldShowEditTab = ref(false)

    // Filter tabs dựa trên điều kiện middleware
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


    const handleSubmitSuccess  = () => {
      changeTab('list')
    }

    const handleShowEdit = data => {
      console.log(data)
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
    }
  },
}
</script>
