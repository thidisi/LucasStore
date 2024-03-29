<script setup>
import { useTheme } from "vuetify"
import VerticalNavSectionTitle from "@/@layouts/components/VerticalNavSectionTitle.vue"
import upgradeBannerDark from "@images/pro/upgrade-banner-dark.png"
import upgradeBannerLight from "@images/pro/upgrade-banner-light.png"
import VerticalNavLayout from "@layouts/components/VerticalNavLayout.vue"
import VerticalNavLink from "@layouts/components/VerticalNavLink.vue"
import VerticalNavGroup from "@layouts/components/VerticalNavGroup.vue"

// Components
import Footer from "@/layouts/components/Footer.vue"
import NavbarThemeSwitcher from "@/layouts/components/NavbarThemeSwitcher.vue"
import UserProfile from "@/layouts/components/UserProfile.vue"

const vuetifyTheme = useTheme()

const upgradeBanner = computed(() => {
  return vuetifyTheme.global.name.value === "light"
    ? upgradeBannerLight
    : upgradeBannerDark
})
</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <IconBtn
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon icon="bx-menu" />
        </IconBtn>

        <!-- 👉 Search -->
        <div
          class="d-flex align-center cursor-pointer"
          style="user-select: none"
        >
          <!-- 👉 Search Trigger button -->
          <IconBtn>
            <VIcon icon="bx-search" />
          </IconBtn>

          <span class="d-none d-md-flex align-center text-disabled">
            <span class="me-3">Search</span>
            <span class="meta-key">&#8984;K</span>
          </span>
        </div>

        <VSpacer />

        <IconBtn
          class="me-2"
          href="https://github.com/themeselection/sneat-vuetify-vuejs-admin-template-free"
          target="_blank"
          rel="noopener noreferrer"
        >
          <VIcon icon="bxl-github" />
        </IconBtn>

        <IconBtn class="me-2">
          <VIcon icon="bx-bell" />
        </IconBtn>

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>

    <template #vertical-nav-content>
      <VerticalNavLink
        :item="{
          title: 'Dashboard',
          icon: 'bx-home',
          to: '/admin/dashboard',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Account Settings',
          icon: 'mdi-account-cog-outline',
          to: '/admin/account-settings',
        }"
      />

      <!-- 👉 Ecommerces -->
      <VerticalNavSectionTitle
        :item="{
          heading: 'Ecommerce',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Menu',
          icon: 'bx-user-plus',
          to: '/admin/menu',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Blog',
          icon: 'bx-user-plus',
          to: '/admin/blogs',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Category',
          icon: 'bx-user-plus',
          to: '/admin/categories',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Attribute',
          icon: 'bx-user-plus',
          to: '/admin/attributes',
        }"
      />

      <VerticalNavLink
        :item="{
          title: 'Production',
          icon: 'bx-user-plus',
          to: '/admin/products',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Discount',
          icon: 'bx-user-plus',
          to: '/admin/discounts',
        }"
      />
      <!-- 👉 Pages -->
      <VerticalNavSectionTitle
        :item="{
          heading: 'Pages',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'User',
          icon: 'bx-user-plus',
          to: '/admin/users',
        }"
      />
      <!-- 👉 Setting -->
      <VerticalNavSectionTitle
        :item="{
          heading: 'Setting',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'About',
          icon: 'bx-user-plus',
          to: '/admin/about',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Slider',
          icon: 'bx-credit-card',
          to: '/admin/slides',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Setting',
          icon: 'bx-credit-card',
          to: '/admin/setting',
        }"
      />
    </template>
    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}
</style>
