import axios from '@/plugins/axios'

const getAllAbout = () => {
  const about = ref([])  
  const error = ref(null)

  const load = async () => {
    try {
      let response = await axios.get(`about`)
      about.value = response.data.about
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }
  
  return { about, error, load }
}

export default getAllAbout
