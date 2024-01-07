<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      class="auth-card pa-4 pt-7"
      max-width="448"
    >
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="d-flex">
            <div
              class="d-flex text-primary"
              v-html="logo"
            />
          </div>
        </template>

        <VCardTitle class="text-2xl font-weight-bold">
          Lucas Store
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          Welcome to lucas store! 👋🏻
        </h5>
        <p class="mb-0">
          Please sign in to your account and start the adventure
        </p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="login">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                autofocus
                placeholder="johndoe@email.com"
                label="Email"
                type="email"
              />
              <span
                v-for="error in v$.email.$errors"
                :key="error.$uid"
                class="text-danger"
              >
                {{ error.$message }}
              </span> 
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                label="Password"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />
              <span
                v-for="error in v$.password.$errors"
                :key="error.$uid"
                class="text-danger"
              >
                {{ error.$message }}
              </span> 

              <!-- remember me checkbox -->
              <div class="d-flex align-center justify-space-between flex-wrap mt-1 mb-4">
                <VCheckbox
                  v-model="form.remember"
                  label="Remember me"
                />

                <RouterLink
                  class="text-primary ms-2 mb-1"
                  to="javascript:void(0)"
                >
                  Forgot Password?
                </RouterLink>
              </div>

              <!-- login button -->
              <VBtn
                block
                type="submit"
              >
                Login
              </VBtn>
            </VCol>

            <!-- create account -->
            <VCol
              cols="12"
              class="text-center text-base"
            >
              <span>New on our platform?</span>
              <RouterLink
                class="text-primary ms-2"
                to="/register"
              >
                Create an account
              </RouterLink>
            </VCol>

            <VCol
              cols="12"
              class="d-flex align-center"
            >
              <VDivider />
              <span class="mx-4">or</span>
              <VDivider />
            </VCol>

            <!-- auth providers -->
            <VCol
              cols="12"
              class="text-center"
            >
              <AuthProvider />
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<script>
import axios from '@/plugins/axios'
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import logo from '@images/logo.svg?raw'
import { useVuelidate } from '@vuelidate/core'
import { email, minLength, required } from '@vuelidate/validators'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'

export default {
  components: {
    AuthProvider,
  },
  setup() {
    const router = useRouter()
    const store = useStore()

    const form = ref({
      email: "",
      password: "",
      remember: false,
    })

    const rules = computed(() => {
      return { 
        email: {
          required,
          email,
        },
        password: {
          required,
          minLength: minLength(8),
        }, 
      }
    })

    const isPasswordVisible = ref(false)
    const v$ = useVuelidate(rules, form)

    const login = async () => {
      try {
        const checkValid = await v$.value.$validate()

        if (checkValid) {
          const { email, password, remember } = form.value

          let token = localStorage.getItem('token')

          const response = await axios.post('/auth/login', {
            email,
            password,
            remember,
          })

          if (token) {
            console.log('Login successful. Token updated:')
            localStorage.setItem('token', response.data.token)
          } else {
            console.log('Login successful. New token created:')
            localStorage.setItem('token', response.data.token)
          }

          store.dispatch('login')
          router.push({ name: 'dashboard' })
        }
      } catch (error) {
        form.password = ''
        console.error('Login error:', error)
      }
    }

    return {
      form,
      login,
      isPasswordVisible,
      v$,
      logo,
    }
  },
}
</script>

<style lang="scss">
@use "@core/scss/template/pages/page-auth.scss";
.text-danger {
  color:red;
  margin-top: 2px;
  margin-left: 4px;
  font-size: 0.9rem;
}
</style>
