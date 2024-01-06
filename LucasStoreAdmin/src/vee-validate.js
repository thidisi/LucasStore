// vee-validate.js
import { createApp } from 'vue'
import {
  Field,
  Form,
  ErrorMessage,
  defineRule,
  configure,
} from 'vee-validate'
import { required, email, min, max } from '@vee-validate/rules'
import { localize } from '@vee-validate/i18n'
import en from '@vee-validate/i18n/dist/locale/en.json'

const app = createApp()

// Register components globally
app.component('Field', Field)
app.component('Form', Form)
app.component('ErrorMessage', ErrorMessage)

// Register rules globally
defineRule('required', required)
defineRule('email', email)
defineRule('min', min)
defineRule('max', max)

// Set the locale to English
configure({
  generateMessage: localize({ en }),
})

// Install the components globally
app.component('Field', Field)
app.component('Form', Form)
app.component('ErrorMessage', ErrorMessage)

export { Field, Form, ErrorMessage }
