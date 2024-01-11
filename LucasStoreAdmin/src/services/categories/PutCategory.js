import axios from '@/plugins/axios'

const PutCategories = () => {
  const error = ref(null)

  const load = async (id, data) => {
    try {
      let response = await axios.post(`categories/${id}`, data, {
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
      let response = await axios.delete(`categories/${id}`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const changStatus = async id => {
    try {
      let response = await axios.post(`categories/changeStatus/${id}`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  return { error, load, destroy, changStatus }
}

export default PutCategories
