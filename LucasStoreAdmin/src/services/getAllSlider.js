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

  
  return { slider, load, error }
}

export default getAllSlider
