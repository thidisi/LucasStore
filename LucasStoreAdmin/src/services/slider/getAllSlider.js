import axios from '@/plugins/axios'

const getAllSlider = () => {
  const slider = ref([])  
  const error = ref(null)

  const load = async () => {
    try {
      let response = await axios.get('/slides')
      slider.value = response.data.slider
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  const load_finder = async id => {
    try {
      let response = await axios.get(`slides/${id}/edit`)
      
      return response.data
    } catch (e) {
      error.value = e.message
      console.log(error.value)
    }
  }

  
  return { slider, error, load, load_finder }
}

export default getAllSlider
