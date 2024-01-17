import axios from '@/plugins/axios'

const PostBlog = () => {
  const error = ref(null)

  const load = async data => {
    try {
      let response = await axios.post(`blogs`, data, {
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

  return { error, load }
}

export default PostBlog
