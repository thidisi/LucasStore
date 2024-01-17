<!-- YourOtherComponent.vue hoặc App.vue -->
<template>
  <div>
    <GetAllList />
  </div>
</template>

<script>
import GetAllList from "@/views/pages/admin/attribute/GetAllList.vue"

export default {
  components: {
    GetAllList,
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
