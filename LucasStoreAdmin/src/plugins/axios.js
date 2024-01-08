import axios from "axios"

// Thiết lập cấu hình Axios
const instance = axios.create({
  baseURL: 'http://localhost:8000/api',
})

instance.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  
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
