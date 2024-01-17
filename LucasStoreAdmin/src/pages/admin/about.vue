<!-- YourOtherComponent.vue hoặc App.vue -->
<template>
  <VRow>
    <VCol cols="12">
      <VCard title="About List">
        <GetDataAbout />
      </VCard>
    </VCol>
  </VRow>
</template>

<script>
import GetDataAbout from "@/views/pages/admin/about/GetDataAbout.vue"

export default {
  components: {
    GetDataAbout,
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
