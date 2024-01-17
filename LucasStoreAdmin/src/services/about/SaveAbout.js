import axios from '@/plugins/axios'

const SaveAbout = () => {
  const error = ref(null)

  const load_store = async data => {
    try {
      let response = await axios.post(`about`, data, {
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

  
  return { error, load_store }
}

export default SaveAbout
