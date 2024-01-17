import axios from '@/plugins/axios'

const GetAllBlogs = () => {
  const blogs = ref([])  
  const error = ref(null)

  const load = async () => {
    try {
      let response = await axios.get(`blogs`)
      blogs.value = response.data.blogs
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const load_finder = async id => {
    try {
      let response = await axios.get(`blogs/${id}/edit`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  
  return { blogs, error, load, load_finder }
}

export default GetAllBlogs
