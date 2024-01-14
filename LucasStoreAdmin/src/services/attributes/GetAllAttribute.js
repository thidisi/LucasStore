import axios from '@/plugins/axios'

const getAllAttribute = () => {
  const attributes = ref([])  
  const error = ref(null)

  const load = async () => {
    try {
      let response = await axios.get(`attributes`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }
  
  return { attributes, error, load }
}

export default getAllAttribute
