import axios from '@/plugins/axios'

const GetAllMajorCategory = () => {
  const major_category = ref([])  
  const error = ref(null)

  const load = async () => {
    try {
      let response = await axios.get('/major_categories')
      major_category.value = response.data.major_categories
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const load_finder = async id => {
    try {
      let response = await axios.get(`major_categories/${id}/edit`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  
  return { major_category, error, load, load_finder }
}

export default GetAllMajorCategory
