export default function (to, from, next) {
  const pageTitle = to.meta.title || 'Admin'

  document.title = `Lucas - ${pageTitle}`
  next()
}
