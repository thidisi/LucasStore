import axios from '@/plugins/axios'

const PostMajorCategories = () => {
  const error = ref(null)

  const load = async data => {
    try {
      let response = await axios.post('/major_categories', data)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  return { error, load }
}

export default PostMajorCategories
