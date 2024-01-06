// src/middleware/authGuard.js
import store from '@/store'

export default function (to, from, next) {
  const isLoggedIn = store.getters.isLoggedIn

  if (to.matched.some(record => record.meta.requiresAuth) && !isLoggedIn) {
    next({ name: 'login' })
  } else {
    next()
  }
}
