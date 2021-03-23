import Modal from 'bootstrap.native/dist/components/modal-native.esm.js'
const getTricks = () => {
  const loadMoreButton = document.getElementById('loadMoreTricks')
  const homepageTricksList = document.getElementById('homeTricksList')

  if (null !== loadMoreButton) {
    loadMoreButton.addEventListener('click', async (event) => {
      const element = event.target
      const url = `${element.dataset.url}/${element.dataset.page}`
      homepageTricksList.classList.add('loading')

      const response = await fetch(url)
      const data = await response.json()
      const trickTemplate = await data.html
      const endMessage = await data.message

      if (trickTemplate !== '') {
        homepageTricksList.innerHTML += trickTemplate
        element.dataset.page++
        if (endMessage !== '') {
          element.innerText = endMessage
          element.setAttribute('disabled', 'disabled')
        }
      }
      homepageTricksList.classList.remove('loading')
    })

  }
}

const deleteModal = () => {
  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('confirmDeleteModal')) {
      const modal = new Modal('#' + e.target.id)
      modal.show()
    }
  })
}

export {
  getTricks,
  deleteModal,
}