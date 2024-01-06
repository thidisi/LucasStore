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
      // Thực hiện logic đăng nhập
      // Sau khi đăng nhập thành công, cập nhật trạng thái xác thực
      // Giả sử logic đăng nhập thành công sau 1 giây
      commit('setAuthentication', true)
    },
    logout({ commit }) {
      // Thực hiện logic đăng xuất
      // Sau khi đăng xuất, cập nhật trạng thái xác thực
      // Giả sử logic đăng xuất thành công sau 1 giây
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
