import Modal from 'bootstrap.native/dist/components/modal-native.esm.js'
import getListener from './loadMore'

const getTricks = () => {
  const loadMoreButton = document.getElementById('loadMoreTricks')

  if (null !== loadMoreButton) {
    loadMoreButton.addEventListener('click', getListener(loadMoreButton))
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

const smoothScrollButton = document.getElementById('scrollDown')
if (null !== smoothScrollButton) {
  smoothScrollButton.addEventListener('click', function (e) {
    e.preventDefault()

    const trickList = document.querySelector('#homeTricksList').offsetTop

    scroll({
      top: trickList,
      behavior: 'smooth',
    })
  })
}

export {
  getTricks,
  deleteModal,
}

