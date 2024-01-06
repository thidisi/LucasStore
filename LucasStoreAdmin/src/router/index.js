import { checkAuthentication } from '@/middleware/authCheck'
import authGuard from '@/middleware/authGuard'
import { createRouter, createWebHistory } from 'vue-router'
import store from '@/store'
import titleMiddleware from '@/middleware/title'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: '/admin/dashboard',
      meta: { requiresAuth: true },
    },
    {
      path: '/',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: 'typography',
          component: () => import('../pages/typography.vue'),
        },
        {
          path: 'icons',
          component: () => import('../pages/icons.vue'),
        },
        {
          path: 'cards',
          component: () => import('../pages/cards.vue'),
        },
        {
          path: 'tables',
          component: () => import('../pages/tables.vue'),
        },
        {
          path: 'form-layouts',
          component: () => import('../pages/form-layouts.vue'),
        },
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      children: [
        {
          path: 'login',
          name: 'login',
          component: () => import('../pages/login.vue'),
          meta: { title: 'Login', requiresAuth: false },
          beforeEnter: (to, from, next) => {
            const isAuthenticated = store.state.isAuthenticated
            if (isAuthenticated) {
              next({ name: 'dashboard' })
            } else {
              next()
            }
          },
        },
        {
          path: 'register',
          component: () => import('../pages/register.vue'),
          meta: { title: 'Register', requiresAuth: false },
          beforeEnter: (to, from, next) => {
            const isAuthenticated = store.state.isAuthenticated
            if (isAuthenticated) {
              next({ name: 'dashboard' })
            } else {
              next()
            }
          },
        },
        {
          path: '/:pathMatch(.*)*',
          component: () => import('../pages/[...all].vue'),
          meta: { requiresAuth: true },
        },
      ],
    },
    {
      path: '/admin',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: 'dashboard',
          name: 'dashboard',
          component: () => import('../pages/dashboard.vue'),
          meta: { title: 'Dashboard', requiresAuth: true },
        },
        {
          path: 'account-settings',
          name: 'account-settings',
          component: () => import('../pages/account-settings.vue'),
          meta: { title: 'Account-settings', requiresAuth: true },
        },
        {
          path: 'setting',
          name: 'setting',
          component: () => import('../pages/admin/about.vue'),
          meta: { title: 'Setting', requiresAuth: true },
        },
        {
          path: 'users',
          name: 'users',
          component: () => import('../pages/admin/user.vue'),
          meta: { title: 'User', requiresAuth: true },
        },
        {
          path: 'attributes',
          name: 'attributes',
          component: () => import('../pages/admin/user.vue'),
          meta: { title: 'Attribute', requiresAuth: true },
        },
        {
          path: 'products',
          name: 'productions',
          component: () => import('../pages/admin/user.vue'),
          meta: { title: 'Production', requiresAuth: true },
        },
        {
          path: 'discounts',
          name: 'discounts',
          component: () => import('../pages/admin/user.vue'),
          meta: { title: 'Discount', requiresAuth: true },
        },
      ],
    },
  ],
})

router.beforeEach(titleMiddleware)

router.beforeEach((to, from, next) => {
  const isAuthenticated = checkAuthentication(store)

  // Kiểm tra meta.requiresAuth của route hiện tại
  if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    next({ name: 'login' })
  } else {
    next()

  }
})

export default router
