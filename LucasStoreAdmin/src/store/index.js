// store.js
import { createStore } from 'vuex'

const store = createStore({
  state: {
    isAuthenticated: false,
  },
  mutations: {
    setAuthentication(state, isAuthenticated) {
      state.isAuthenticated = isAuthenticated
    },
  },
  actions: {
    login({ commit }) {
      commit('setAuthentication', true)
    },
    logout({ commit }) {
      commit('setAuthentication', false)
    },
  },
  plugins: [store => {
    // Lưu trạng thái xác thực vào Local Storage
    store.subscribe((mutation, state) => {
      localStorage.setItem('isAuthenticated', state.isAuthenticated)
    })
  }],
})

export default store
