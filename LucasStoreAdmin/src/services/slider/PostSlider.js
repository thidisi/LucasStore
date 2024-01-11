import axios from '@/plugins/axios'

const PostSlider = () => {
  const error = ref(null)

  const load = async data => {
    try {
      let response = await axios.post('/slides', data, {
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

  const getMenu = async () => {
    try {
      let response = await axios.get('/slides')
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  return { error, load, getMenu }
}

export default PostSlider
