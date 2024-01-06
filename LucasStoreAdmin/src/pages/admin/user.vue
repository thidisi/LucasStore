<!-- YourOtherComponent.vue hoặc App.vue -->
<template>
  <VRow>
    <VCol cols="12">
      <VCard title="User List">
        <DataUserList />
      </VCard>
    </VCol>
  </VRow>
</template>

<script>
import DataUserList from '@/views/pages/admin/user/DataUserList.vue'

export default {
  components: {
    DataUserList,
  },
}
</script>
