import axios from '@/plugins/axios'

const PutMajorCategories = () => {
  const error = ref(null)

  const load = async (id, data) => {
    try {
      let response = await axios.post(`major_categories/${id}`, data, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const destroy = async id => {
    try {
      let response = await axios.delete(`major_categories/${id}`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  return { error, load, destroy }
}

export default PutMajorCategories
