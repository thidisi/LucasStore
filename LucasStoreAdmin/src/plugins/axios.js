import axios from "axios"

// Thiết lập cấu hình Axios
const instance = axios.create({
  baseURL: 'https://api.example.com', // Đặt baseURL của bạn
})

// Intercept trước mỗi yêu cầu
instance.interceptors.request.use(config => {
  // Thêm logic xử lý token ở đây (nếu cần)
  // Ví dụ: lấy token từ localStorage và thêm vào header Authorization
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
}, error => {
  return Promise.reject(error)
})

// Intercept sau mỗi phản hồi
instance.interceptors.response.use(response => {
  // Xử lý dữ liệu phản hồi ở đây (nếu cần)

  return response
}, error => {
  // Xử lý lỗi phản hồi ở đây (nếu cần)

  return Promise.reject(error)
})

export default instance
