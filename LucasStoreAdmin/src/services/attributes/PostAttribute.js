import axios from '@/plugins/axios'

const PostAttributes = () => {
  const error = ref(null)

  const load_store = async data => {
    try {
      let response = await axios.post(`attributes`, data, {
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

  const load_update = async (id, data) => {
    try {
      let response = await axios.post(`attributes/${id}`, data, {
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
      let response = await axios.delete(`attributes/${id}`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const changStatus = async id => {
    try {
      let response = await axios.post(`attributes/changeStatus/${id}`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }


  return { error, load_store, load_update, destroy, changStatus }
}

export default PostAttributes
