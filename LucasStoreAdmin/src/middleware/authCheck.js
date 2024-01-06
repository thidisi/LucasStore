// src/middleware/authCheck.js

import store from '@/store'

export function checkAuthentication(store) {
  return store.state.isAuthenticated
}
