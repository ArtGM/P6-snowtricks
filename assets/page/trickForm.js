import 'bootstrap'
import bsCustomFileInput from 'bs-custom-file-input'
import * as url from 'url'

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

const removeImageElements = document.querySelectorAll('.remove-image')
const removeImage = Array.from(removeImageElements)

const removeVideoElements = document.querySelectorAll('.remove-video')
const removeVideo = Array.from(removeVideoElements)

addImageButton.addEventListener('click', () => {
  const newImageWidget = getImagePrototype.replace(/__name__/g, imageCounter)
  imageCounter++
  trickFormImageField.setAttribute('data-widget-counter', imageCounter)
  const colId = 'col-id-' + imageCounter

  const column = document.createElement('div')
  const cardLayout = document.createElement('div')
  const deleteButton = document.createElement('button')

  deleteButton.classList.add('btn')
  deleteButton.classList.add('btn-danger')
  deleteButton.classList.add('remove-image')
  deleteButton.append('delete')
  deleteButton.setAttribute('data-id', colId)
  deleteButton.setAttribute('type', 'button')
  column.classList.add('col-4')
  column.setAttribute('id', colId)
  cardLayout.classList.add('card')
  cardLayout.classList.add('p-3')
  cardLayout.innerHTML = newImageWidget

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

  deleteVideoButton.classList.add('btn')
  deleteVideoButton.classList.add('btn-danger')
  deleteVideoButton.classList.add('remove-video')
  deleteVideoButton.append('X')
  deleteVideoButton.setAttribute('data-id', fieldId)
  deleteVideoButton.setAttribute('type', 'button')

  row.setAttribute('id', fieldId)

  row.innerHTML = newVideoWidget
  row.appendChild(deleteVideoButton)
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
    const container = parent.parentNode

    const getMainSelector = container.getAttribute('id')
    const title = document.getElementById(getMainSelector + '_title')
    const description = document.getElementById(
      getMainSelector + '_description')

    const url = new URL(e.target.value).searchParams
    const videoId = url.get('v')

    if (videoId !== null) {
      const videoThumbnail = document.createElement('img')
      fetch(
        `https://www.googleapis.com/youtube/v3/videos?id=${videoId}&key=${ytApiKey}&part=snippet`).
        then(response => response.json()).
        then(data => {
          videoThumbnail.setAttribute('src',
            data.items[0].snippet.thumbnails.medium.url)
          title.value = data.items[0].snippet.title
          description.value = data.items[0].snippet.description
          parent.insertBefore(videoThumbnail, element)
          return data.items[0].snippet
        })
    }
    else {
      alert(
        'Merci de saisir une adresse youtube sous la forme https://www.youtube.com/watch?v=XXXXXXX')
    }

  }
})



