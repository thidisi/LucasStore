import axios from '@/plugins/axios'

const PutBlog = () => {
  const error = ref(null)

  const load = async (id, data) => {
    try {
      let response = await axios.post(`blogs/${id}`, data, {
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
      let response = await axios.delete(`blogs/${id}`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const changStatus = async id => {
    try {
      let response = await axios.post(`blogs/changeStatus/${id}`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  return { error, load, destroy, changStatus }
}

export default PutBlog
