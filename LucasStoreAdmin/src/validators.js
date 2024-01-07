import { helpers } from '@vuelidate/validators'

export const allowedFileTypes = types => helpers.withParams({ type: 'allowedFileTypes', allowedTypes: types }, value => {
  if (!value) return true

  const fileType = value.type
  
  return types.includes(fileType)
})

export const maxSize = size => helpers.withParams({ type: 'maxSize', maxSize: size }, value => {
  if (!value) return true

  const fileSize = value.size
  
  return fileSize <= size
})
