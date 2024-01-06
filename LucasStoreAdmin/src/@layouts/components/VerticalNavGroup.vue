<!-- NavGroup.vue -->
<script setup>
import { defineProps } from 'vue'
import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue'
import VerticalNavLink from '@/@layouts/components/VerticalNavLink.vue'

const props = defineProps({
  group: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <li
    class="nav-group"
    :class="{ disabled: group.disable }"
  >
    <div class="nav-group-label">
      <!-- 👉 Title -->
      <VIcon
        :icon="group.icon"
        class="nav-item-icon"
      />
      <span class="nav-item-title">
        {{ group.title }}
      </span>
    </div>
    <ul class="nav-group-children">
      <li
        v-for="item in group.items"
        :key="item.title"
        class="nav-link"
        :class="{ disabled: item.disable }"
      >
        <Component
          :is="item.to ? 'RouterLink' : 'a'"
          class="pm-0"
          :to="item.to"
          :href="item.href"
        >
          <VIcon
            :icon="item.icon"
            class="nav-item-icon"
          />
          <!-- 👉 Title -->
          <span class="nav-item-title">
            {{ item.title }}
          </span>
        </Component>
      </li>
    </ul>
  </li>
</template>
