import axios from "axios"

// Thiết lập cấu hình Axios
const instance = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
})

instance.interceptors.request.use(config => {

  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  config.headers['X-Requested-With'] = 'XMLHttpRequest'
  
  return config
}, error => {
  return Promise.reject(error)
})

instance.interceptors.response.use(response => {

  return response
}, error => {

  return Promise.reject(error)
})

export default instance
