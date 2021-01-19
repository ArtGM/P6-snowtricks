const addImageButton = document.getElementById('addImageButton')
const trickFormImageField = document.getElementById('imagesFieldsList')
let counter = trickFormImageField.getAttribute('data-widget-counter') ||
  trickFormImageField.children.length
const getPrototype = trickFormImageField.getAttribute('data-prototype')
const removeImage = document.querySelector('.remove-image')

//addRemoveEventToButton(removeImage)

addImageButton.onclick = () => {
  console.warn('toto')
  const newWidget = getPrototype.replace(/__name__/g, counter)
  counter++
  trickFormImageField.setAttribute('data-widget-counter', counter)
  const colId = 'col-id-' + counter

  const column = document.createElement('div')
  const cardLayout = document.createElement('div')
  const deleteButton = document.createElement('button')

  deleteButton.classList.add('btn')
  deleteButton.classList.add('btn-danger')
  deleteButton.classList.add('remove-image')
  deleteButton.append('x')
  deleteButton.setAttribute('data-col-id', colId)
  deleteButton.setAttribute('type', 'button')
  column.classList.add('col-4')
  column.setAttribute('id', colId)
  cardLayout.classList.add('card')
  cardLayout.classList.add('p-3')
  cardLayout.innerHTML = newWidget

  cardLayout.appendChild(deleteButton)
  column.appendChild(cardLayout)
  trickFormImageField.appendChild(column)

  if (removeImage !== null) {
    removeImage.addEventListener('click', function (e) {
      let colIdToDelete = e.target.getAttribute('data-col-id')
      let coltodelete = document.getElementById(colIdToDelete)
      coltodelete.parentNode.removeChild(coltodelete)
    })
  }

  console.warn(deleteButton)
  addRemoveEventToButton(deleteButton)
}

function addRemoveEventToButton (elem) {
  elem.addEventListener('click', function (e) {
    let colIdToDelete = e.target.getAttribute('data-col-id')
    let coltodelete = document.getElementById(colIdToDelete)
    coltodelete.parentNode.removeChild(coltodelete)
  })
}

