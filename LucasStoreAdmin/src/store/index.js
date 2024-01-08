// store.js
import { createStore } from 'vuex'

const store = createStore({
  state: {
    isAuthenticated: localStorage.getItem('isAuthenticated') === 'true',
    userInfo: null,
  },
  mutations: {
    setAuthentication(state, isAuthenticated) {
      state.isAuthenticated = isAuthenticated
    },
    setUserInfo(state, userInfo) {
      state.userInfo = userInfo
    },
  },
  actions: {
    login({ commit }, userInfo) {
      commit('setAuthentication', true)
      commit('setUserInfo', userInfo)
    },
    logout({ commit }, userInfo) {
      commit('setAuthentication', false)
      commit('setUserInfo', null)
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
