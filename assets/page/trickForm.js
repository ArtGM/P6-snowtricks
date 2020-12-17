const addImageButton = document.getElementById('addImageButton')
const trickFormImageField = document.getElementById('imagesFieldsList')
let counter = trickFormImageField.getAttribute('data-widget-counter') ||
  trickFormImageField.children.length
const getPrototype = trickFormImageField.getAttribute('data-prototype')

addImageButton.onclick = () => {
  const newWidget = getPrototype.replace(/__name__/g, counter)
  counter++
  trickFormImageField.setAttribute('data-widget-counter', counter)
  const listTags = document.createElement('li')
  listTags.classList.add('list-group-item')
  listTags.innerHTML = newWidget
  trickFormImageField.appendChild(listTags)
}


