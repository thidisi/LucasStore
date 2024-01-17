<template>
  <div class="p-4">
    <VForm @submit.prevent="handleSubmit">
      <VRow>
        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.title"
            label="Title"
            :rules="v$.title.$invalid ? [v$.title.required.$invalid ? v$.title.required.$message : '' + v$.title.minLength.$message] : ''"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.address"
            label="Address"
            :rules="v$.address.required.$invalid ? [v$.address.required.$message] : ''"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.address_second"
            label="Address Seconds(*)"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.branch"
            label="Branch"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.branch_second"
            label="Branch Seconds(*)"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.email"
            label="Email"
            :rules="v$.email.required.$invalid ? [v$.email.required.$message] : ''"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.phone"
            label="Phone"
            :rules="v$.phone.required.$invalid ? [v$.phone.required.$message] : ''"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.phone_second"
            label="Phone Seconds(*)"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.link_address_fb"
            label="Link Address Fb (*)"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.link_address_youtube"
            label="Link Address Youtube (*)"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.link_address_zalo"
            label="Link Address Zalo (*)"
          />
        </VCol>

        <VCol
          cols="12"
          md="6"
        >
          <VTextField
            v-model="form.link_address_instagram"
            label="Link Address Instagram (*)"
          />
        </VCol>

        <VCol
          cols="6"
          class="mt-4"
        >
          <VCardText class="d-flex">
            <!-- 👉 Image -->
            <VAvatar
              rounded="lg"
              size="100"
              class="me-6"
              :image="renderImg"
            />
            <!-- 👉 Upload Photo -->
            <form class="d-flex flex-column justify-center gap-3">
              <div class="d-flex flex-wrap gap-2">
                <VBtn
                  color="primary"
                  @click="refInputEl?.click()"
                >
                  <VIcon
                    icon="bx-cloud-upload"
                    class="d-sm-none"
                  />
                  <span class="d-none d-sm-block">Upload New Logo</span>
                </VBtn>

                <input
                  ref="refInputEl"
                  type="file"
                  name="file"
                  accept=".jpeg,.png,.jpg,GIF"
                  hidden
                  @input="handleFileChange"
                >
                <VBtn
                  type="reset"
                  color="error"
                  variant="tonal"
                  @click="resetImage(about?.logo)"
                >
                  <span class="d-none d-sm-block">Reset</span>
                  <VIcon
                    icon="bx-refresh"
                    class="d-sm-none"
                  />
                </VBtn>
              </div>

              <p class="text-body-1 mb-0">
                Allowed JPG, GIF or PNG. Max size of 800K
              </p>
            </form>
          </VCardText>
        </VCol>

        <VDivider />
  
        <VCol
          cols="12"
          class="d-flex gap-4"
        >
          <VBtn
            type="submit"
            :disabled="isCreateButtonDisabled"
          >
            Submit
          </VBtn>
  
          <VBtn
            type="button"
            color="secondary"
            variant="tonal"
            @click="resetForm(about)"
          >
            Reset
          </VBtn>
        </VCol>
      </VRow>
    </VForm>
  </div>
</template>

<script>
import GetAllAbout from '@/services/about/GetAllAbout'
import SaveAbout from '@/services/about/SaveAbout'
import { useVuelidate } from '@vuelidate/core'
import { minLength, required } from '@vuelidate/validators'
import { allowedFileTypes, maxSize } from '@/validators'

export default {
  setup() {
    const { about, load } = GetAllAbout()
    const { load_store } = SaveAbout()

    const urlImage = import.meta.env.VITE_API_URL_IMAGE

    const refInputEl = ref()
    const renderImg = ref()

    const form = ref({
      title: '',
      logo: null,
      email: null,
      phone: null,
      phone_second: null,
      address: null,
      address_second: null,
      branch: null,
      branch_second: null,
      link_address_fb: null,
      link_address_youtube: null,
      link_address_zalo: null,
      link_address_instagram: null,
    })

    const rules = computed(() => {
      return { 
        title: {
          required,
          minLength: minLength(4),
        },
        logo: {
          allowedFileTypes: allowedFileTypes(['jpeg', 'png', 'jpg', 'gif']),
          maxSize: maxSize(800 * 1024),
        },
        email: {
          required,
        },
        phone: {
          required,
        },
        address: {
          required,
        },
      }
    })

    const isCreateButtonDisabled = ref(false)

    const v$ = useVuelidate(rules, ref(form))

    const handleFileChange = event => {
      form.value.logo = event.target.files[0]

      const fileReader = new FileReader()
      const { files } = event.target
      if (files && files.length) {
        fileReader.readAsDataURL(files[0])
        fileReader.onload = () => {
          if (typeof fileReader.result === 'string')
            renderImg.value = fileReader.result
        }
      }
    }

    const resetImage = item => {
      form.value.logo = null
      renderImg.value = urlImage + item
    }

    const resetForm = item => {
      form.value.title = item.title,
      form.value.logo = null,
      form.value.email = item.email,
      form.value.phone = item.phone,
      form.value.phone_second = item.phone_second,
      form.value.address = item.address,
      form.value.address_second = item.address_second,
      form.value.branch = item.branch,
      form.value.branch_second = item.branch_second,
      form.value.link_address_fb = item.link_address_fb,
      form.value.link_address_youtube = item.link_address_youtube,
      form.value.link_address_zalo = item.link_address_zalo,
      form.value.link_address_instagram = item.link_address_instagram,
      renderImg.value = item.logo ? urlImage + item.logo : null
      v$.value.$reset()
    }

    // eslint-disable-next-line sonarjs/cognitive-complexity
    const handleSubmit = async () => {
      try {
        const checkValid = await v$.value.$validate()

        if (checkValid) {
          const { title, logo, email, phone, phone_second, address, address_second, branch, branch_second, link_address_fb, link_address_youtube, link_address_zalo, link_address_instagram  } = form.value

          const formData = new FormData()
  
          formData.append('title', title)
          formData.append('logo', logo ? logo : '')
          formData.append('email', email)
          formData.append('phone', phone)
          formData.append('phone_second', phone_second ? phone_second : '')
          formData.append('address', address)
          formData.append('address_second', address_second ? address_second : '')
          formData.append('branch', branch ? branch : '')
          formData.append('branch_second', branch_second ? branch_second : '')
          formData.append('link_address_fb', link_address_fb ? link_address_fb : '')
          formData.append('link_address_youtube', link_address_youtube ? link_address_youtube : '')
          formData.append('link_address_zalo', link_address_zalo ? link_address_zalo : '')
          formData.append('link_address_instagram', link_address_instagram ? link_address_instagram : '')
          let result = await load_store(formData)
          about.value = result.about
          resetForm(result.about)
        }
      } catch (error) {
        resetForm(about.value)
        console.error('Error:', error)
      }finally {
        setTimeout(() => {
          isCreateButtonDisabled.value = false
        }, 1000)
      }

    }

    const renderLoad = async () => {
      try {
        await load()
        resetForm(about.value)
      } catch (error) {
        console.error('Error:', error)
      }
    }

    onMounted(() => {
      renderLoad()
    })

    return {
      urlImage,
      about,
      form,
      handleSubmit,
      v$,
      isCreateButtonDisabled,
      resetForm,
      refInputEl,
      handleFileChange,
      resetImage,
      renderImg,
    }
  },
}
</script>
