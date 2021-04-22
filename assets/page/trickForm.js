import 'bootstrap'
import bsCustomFileInput from 'bs-custom-file-input'

bsCustomFileInput.init()

const addImageButton = document.getElementById('addImageButton')
const addVideoButton = document.getElementById('addVideoButton')

const trickFormImageField = document.getElementById('imagesFieldsList')
const trickFormVideoField = document.getElementById('videoFieldsList')

const ytApiKey = trickFormVideoField.getAttribute('data-youtube-api')

let imageCounter = trickFormImageField.getAttribute('data-widget-counter') ||
  trickFormImageField.children.length
let videoCounter = trickFormVideoField.getAttribute('data-widget-counter') ||
  trickFormVideoField.children.length

const getImagePrototype = trickFormImageField.getAttribute('data-prototype')
const getVideoPrototype = trickFormVideoField.getAttribute('data-prototype')


addImageButton.addEventListener('click', () => {
  const newImageWidget = getImagePrototype.replace(/__name__/g, imageCounter)
  imageCounter++
  trickFormImageField.setAttribute('data-widget-counter', imageCounter)
  const colId = 'col-id-' + imageCounter

  const column = document.createElement('div')
  column.classList.add('col-12', 'col-md-4', 'my-2')
  column.setAttribute('id', colId)

  const cardLayout = document.createElement('div')
  cardLayout.classList.add('card', 'p-3')
  cardLayout.innerHTML = newImageWidget

  const deleteButton = document.createElement('button')
  const deleteButtonClassList = ['btn', 'btn-danger', 'remove-image']
  deleteButton.classList.add(...deleteButtonClassList)

  deleteButton.append('delete')
  deleteButton.setAttribute('data-id', colId)
  deleteButton.setAttribute('type', 'button')

  cardLayout.appendChild(deleteButton)
  column.appendChild(cardLayout)
  trickFormImageField.appendChild(column)

})

addVideoButton.addEventListener('click', () => {
  const newVideoWidget = getVideoPrototype.replace(/__name__/g, videoCounter)
  videoCounter++
  trickFormVideoField.setAttribute('data-widget-counter', videoCounter)
  const fieldId = 'field-id-' + videoCounter

  const row = document.createElement('li')

  const deleteVideoButton = document.createElement('button')
  const deleteButtonClassList = ['btn', 'btn-sm', 'btn-danger', 'remove-video']

  deleteVideoButton.classList.add(...deleteButtonClassList)

  deleteVideoButton.append('X')
  deleteVideoButton.setAttribute('data-id', fieldId)
  deleteVideoButton.setAttribute('type', 'button')
  row.classList.add('list-group-item', 'video-field')
  row.setAttribute('id', fieldId)
  row.innerHTML = newVideoWidget
  row.appendChild(deleteVideoButton)

  const content = row.firstChild
  content.classList.add('d-flex', 'justify-start', 'align-center')
  trickFormVideoField.appendChild(row)

})

document.addEventListener('click', function (e) {
  let IdToDelete = e.target.getAttribute('data-id')
  if (IdToDelete) {
    let elementToDelete = document.getElementById(IdToDelete)
    if (e.target && e.target.classList.contains('remove-image')) {
      imageCounter--
      trickFormImageField.setAttribute('data-widget-counter', imageCounter)
    }
    if (e.target && e.target.classList.contains('remove-video')) {
      videoCounter--
      trickFormVideoField.setAttribute('data-widget-counter', videoCounter)
    }
    elementToDelete.parentNode.removeChild(elementToDelete)
  }
})

// Handle youtube

//https://www.googleapis.com/youtube/v3/videos?id={videoId}&key={myApiKey}&part=snippet&callback=?

document.addEventListener('change', function (e) {
  if (e.target && e.target.classList.contains('watchChange')) {

    const element = document.getElementById(e.target.id)
    const parent = element.parentNode
    const subParent = parent.parentNode
    const container = parent.parentNode

    const getMainSelector = container.getAttribute('id')
    const title = document.getElementById(getMainSelector + '_title')
    const description = document.getElementById(
      getMainSelector + '_description')

    const url = new URL(e.target.value).searchParams
    const videoId = url.get('v')

    if (videoId !== null) {
      const videoThumbnail = document.createElement('img')
      videoThumbnail.classList.add('img-thumbnail', 'mr-2')
      fetch(
        `https://www.googleapis.com/youtube/v3/videos?id=${videoId}&key=${ytApiKey}&part=snippet`).
        then(response => response.json()).
        then(data => {
          console.log(data)
          videoThumbnail.setAttribute('src',
            data.items[0].snippet.thumbnails.default.url)
          title.value = data.items[0].snippet.title
          description.value = data.items[0].snippet.description
          subParent.insertBefore(videoThumbnail, parent)
          return data.items[0].snippet
        })
    }
    else {
      alert(
        'Merci de saisir une adresse youtube sous la forme https://www.youtube.com/watch?v=XXXXXXX')
    }

  }
})



