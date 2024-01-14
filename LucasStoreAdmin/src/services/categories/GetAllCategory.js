import axios from '@/plugins/axios'

const getAllCategory = () => {
  const categories = ref([])  
  const error = ref(null)

  const load = async () => {
    try {
      let response = await axios.get(`categories`)
      categories.value = response.data.categories
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const load_finder = async id => {
    try {
      let response = await axios.get(`categories/${id}/edit`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  
  return { categories, error, load, load_finder }
}

export default getAllCategory
