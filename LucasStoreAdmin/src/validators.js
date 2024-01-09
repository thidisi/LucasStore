import { helpers } from '@vuelidate/validators'

export const allowedFileTypes = types => helpers.withParams(
  { type: 'allowedFileTypes', allowedTypes: types },
  value => {
    if (!value) {
      return 'File is required.'
    }

    const fileType = value.type

    if (!types.includes(fileType)) {
      return `Invalid file type. Allowed types are: ${types.join(', ')}.`
    }

    return true
  },
)

export const maxSize = size => helpers.withParams(
  { type: 'maxSize', maxSize: size },
  value => {
    if (!value) {
      return 'File is required.'
    }

    const fileSize = value.size

    if (fileSize > size) {
      return `File size exceeds the maximum allowed size of ${size} bytes.`
    }

    return true
  },
)
