import axios from '@/plugins/axios'

const getAllAttribute = () => {
  const attributes = ref([])  
  const error = ref(null)

  const load = async () => {
    try {
      let response = await axios.get('/attributes')
      attributes.value = response.data.attr
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const load_finder = async id => {
    try {
      let response = await axios.get(`attributes/${id}/edit`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  
  return { attributes, error, load, load_finder }
}

export default getAllAttribute
