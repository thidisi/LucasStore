<script setup>
import { useTheme } from 'vuetify'
import { hexToRgb } from '@layouts/utils'
import '@/assets/css/index.css'

const { global } = useTheme()
</script>

<template>
  <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
    <RouterView />
  </VApp>
</template>
