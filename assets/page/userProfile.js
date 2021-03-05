import Modal from 'bootstrap.native/dist/components/modal-native.esm.js'
import bsCustomFileInput from 'bs-custom-file-input'

bsCustomFileInput.init()

const submitFormButton = document.getElementById('submitForm')
const avatarForm = document.getElementById('js-handle-avatar')
const handleUrl = avatarForm.getAttribute('data-avatar-url')
const uploadAvatarModal = new Modal('#avatarChange')
const imgAvatar = document.getElementById('userAvatar')
const modalContent = document.querySelector('.modal-content')
const inputUserAvatarId = document.getElementById('user_profile_form_avatar')

imgAvatar.addEventListener('click', function (e) {
    uploadAvatarModal.show()
})

avatarForm.addEventListener('submit', function (e) {
    e.preventDefault()

    const formData = new FormData(avatarForm)

    fetch(handleUrl, {
        method: 'post',
        body: formData,
    }).then(response => {
        return response.text()
    }).then(response => {
        if (isJson(response)) {
            const data = JSON.parse(response)
            uploadAvatarModal.hide()
            imgAvatar.setAttribute('src', data.newAvatar)
            inputUserAvatarId.value = data.newAvatarId
            imgAvatar.insertAdjacentHTML('beforebegin',
              '<small class="alert alert-warning">Save your change !</small>')
        }
        else {
            modalContent.innerHTML = response
        }
        //else
    })
})

function isJson (str) {
    try {
        JSON.parse(str)
    }
    catch (e) {
        return false
    }
    return true
}
